<?php

namespace app\models;

use Yii;
use Mollie_API_Client;
use Mollie_API_Object_Method;
use yii\helpers\ArrayHelper;
use app\models\Transacties;

/**
 * This is the model class for Mollie intergration.
 */
class Mollie extends Transacties
{
    public $mollie;
    public $issuer;
    public $parameters;
    public $automatische_betaling;

    function __construct()
    {
        parent::__construct();
        $this->mollie = new Mollie_API_Client;
        $this->mollie->setApiKey( Yii::$app->params['mollie']['test']);

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['issuer'], 'required'];
        $rules[] = [['automatische_betaling'], 'safe'];
        
        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issuer' => 'Bank',
            'transacties_user_id' => 'Betaling voor',
        ];
    }

    public function getIssuersOptions(){
        $issuers = $this->mollie->issuers->all();
        $list = [];
		foreach ($issuers as $issuer)
		{
			if ($issuer->method == Mollie_API_Object_Method::IDEAL)
			{
				$list[] = $issuer;
			}
		}
        return ArrayHelper::map($list, 'id', 'name');
    }

    public function automatischOphogen() {
        $mollie = new Mollie;
        $users = User::find()
            ->where('ISNULL(blocked_at)')
            ->andWhere('automatische_betaling = TRUE')
            ->andWhere(['not', ['mollie_customer_id' => NULL]])
            ->all();
        $count = 0;
        foreach ($users as $user)
        {
            if($user->getBalans() < 0 && $mollie->checkUserMandates($user->mollie_customer_id) ) {
                $transactie = new Transacties;
                $transactie->transacties_user_id = $user->id;
                $transactie->omschrijving = 'Automatisch ophogen BisonBar';
                $transactie->bedrag = $user->mollie_bedrag;
                $transactie->type_id = BetalingType::getIdealId();
                $transactie->status = self::STATUS_ingevoerd;
                $transactie->datum = date("Y-m-d");
                if (!$transactie->save()) {
                    throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
                }

                $mollie['parameters'] = [
                    'amount'        => $user->mollie_bedrag,
                    'customerId'    => $user->mollie_customer_id,
                    'recurringType' => 'recurring',       // important
                    'description'   => $transactie->omschrijving,
                    "metadata"     => [
                        "transacties_id" => $transactie->transacties_id,
                    ],
                ];
                $mollie->createPayment();
            } else {
                continue;
            }

            $count++;
        }
        return $count;
    }

    public function checkUserMandates($mollie_user_id){
        $mandates = $this->mollie->customers_mandates->withParentId($mollie_user_id)->all();
        foreach ($mandates->data as $key => $mandate) {
            if($mandate->status === 'valid') {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function setParameters() {
        /*
         * Payment parameters:
         *   amount        Amount in EUROs. This example creates a € 27.50 payment.
         *   method        Payment method "ideal".
         *   description   Description of the payment.
         *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
         *   webhookUrl    Webhook location, used to report when the payment changes state.
         *   metadata      Custom metadata that is stored with the payment.
         *   issuer        The customer's bank. If empty the customer can select it later.
         */
        $this->parameters = [
            "amount"       => $this->bedrag,
            "method"       => Mollie_API_Object_Method::IDEAL,
            "description"  => $this->omschrijving,
            "redirectUrl"  => "https://popupbar.biologenkantoor.nl/index.php?r=mollie/return-betaling&transacties_id={$this->transacties_id}",
            "webhookUrl"   => "https://popupbar.biologenkantoor.nl/index.php?r=mollie/webhook",
//                        "redirectUrl"  => "https://bar.debison.nl/index.php?r=mollie/return?transacties_id={$model->transacties_id}",
//                        "webhookUrl"   => "https://bar.debison.nl/index.php?r=mollie/webhook",
            "metadata"     => [
                "transacties_id" => $this->transacties_id,
            ],
            "issuer"       => !empty($this->issuer) ? $this->issuer : NULL
        ];
    }
    
    public  function createUser() {
        if(!isset($this->transacties_user_id)) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
        }

        $user = User::findOne($this->transacties_user_id);
        try
        {
            $customer = $this->mollie->customers->create([
                "name"  => $user->username,
                "email" => $user->email,
            ]);

            $this->parameters['customerId'] = $customer->id;
            $this->parameters['recurringType'] = 'first';
            $user->automatische_betaling = TRUE;
            $user->mollie_customer_id = $customer->id;
            $user->mollie_bedrag = $this->bedrag;
            $user->save();
        }
        catch (Mollie_API_Exception $e)
        {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }

    public function createPayment()
    {
        try
        {
            $payment = $this->mollie->payments->create($this->parameters);

            /*
             * In this example we store the order with its payment status in a database.
             */
            $this->setRetrievedMollieStatus($payment->status);
            $this->mollie_id = $payment->id;
            if(!$this->save()) {
                foreach ($this->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
                return FALSE;
            }
            /*
             * Send the customer off to complete the payment.
             */
            return $payment->getPaymentUrl();

        }
        catch (Mollie_API_Exception $e)
        {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }
}