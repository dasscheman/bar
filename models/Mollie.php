<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Types\PaymentMethod;

/**
 * This is the model class for Mollie intergration.
 */
class Mollie extends Transacties
{
    public $mollie;
    public $issuer;
    public $parameters;
    public $automatische_betaling;

    public function __construct()
    {
        parent::__construct();
        $this->mollie = new MollieApiClient;

        if ($_ENV['YII_ENV'] === 'prod') {
            $this->mollie->setApiKey($_ENV['MOLLIE_LIVE_KEY']);
        } else {
            $this->mollie->setApiKey($_ENV['MOLLIE_TEST_KEY']);
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['betaling'] = ['issuer', 'omschrijving', 'bedrag', 'automatische_betaling', 'transacties_user_id']; //Scenario Values Only Accepted
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['issuer', 'omschrijving', 'bedrag'], 'required', 'on' => 'betaling'];
        $rules[] = [['automatische_betaling', 'transacties_user_id'], 'safe'];

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

    public function getIssuersOptions()
    {
        $this->mollie = new MollieApiClient;
        $this->mollie->setApiKey($_ENV['MOLLIE_TEST_KEY']);
        $issuers = $this->mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);
        return ArrayHelper::map($issuers->issuers, 'id', 'name');
    }

    public function automatischOphogen()
    {
        $users = User::find()
            ->where('ISNULL(blocked_at)')
            ->andWhere('automatische_betaling = TRUE')
            ->andWhere(['not', ['mollie_customer_id' => null]])
            ->all();
        $count = 0;

        echo 'volgende automatisch ophogen controleren:';
        foreach ($users as $user) {
            $mollie = new Mollie;
            // Wanneer een user een pending transactie heeft, dan gaan we niet
            // een nieuwe transactie opstarten.
            $username = $user->getProfile()->one()->voornaam . " " . $user->getProfile()->one()->achternaam;
            echo "\r\n";
            echo '-->' . $username;
            if ($user->getBalans() > $user->getProfile()->one()->limit_ophogen ) {
                echo $user->getBalans();
                echo $user->getProfile()->one()->limit_ophogen;
                echo "--Balans is okey";
                continue;
            }
            if(!$mollie->checkUserMandates($user->mollie_customer_id)) {
                echo "--Geen mandaat";
                continue;
            }
            if($mollie->pendingTransactionsExists($user->id)) {
                echo "--Er loopt al een nog niet afgeronde incasso.";
                continue;
            }
            echo "\r\n";
            $mollie->transacties_user_id = $user->id;
            $mollie->omschrijving = 'Automatisch ophogen BisonBar voor ' . $username . ', met ' . number_format($user->mollie_bedrag, 2, ',', ' ') . ' Euro';
            $mollie->bedrag = $user->mollie_bedrag;
            $mollie->type_id = BetalingType::getIdealId();
            $mollie->status = self::STATUS_ingevoerd;
            $mollie->datum = date("Y-m-d");
            if (!$mollie->save()) {
                foreach ($mollie->errors as $key => $error) {
                    echo 'Fout met opslaan: ' . $key . ':' . $error[0];
                }
                continue;
            }

            $mollie->parameters['amount']['currency'] = "EUR";
            $mollie->parameters['amount']['value'] = $user->mollie_bedrag;
            $mollie->parameters['customerId'] = $user->mollie_customer_id;
            $mollie->parameters['sequenceType'] = 'recurring';       // important
            $mollie->parameters['description'] = $mollie->omschrijving;
            $mollie->parameters["metadata"] = [
                    "transacties_id" => $mollie->transacties_id,
                ];

            $mollie->parameters['webhookUrl'] = "https://" . $_ENV['URL'] . "/index.php?r=mollie/webhook";

            $payment = $mollie->createPayment();

            $message = Yii::$app->mailer->compose('mail_incasso_notificatie', [
                    'user' => $user,
                    'transactie' => $mollie,
                ])
                ->setFrom($_ENV['ADMIN_EMAIL'])
                ->setTo($user->email)
                ->setSubject('Incasso betaling Bison bar');
            if (!empty($user->profile->public_email)) {
                $message->setCc($user->profile->public_email);
            }
            $message->send();
            $count++;
        }
        return $count;
    }

    public function checkUserMandates($mollie_user_id)
    {
        $mandates = $this->mollie->customers_mandates->withParentId($mollie_user_id)->all();
        foreach ($mandates->data as $key => $mandate) {
            if ($mandate->status === 'valid') {
                return true;
            }
        }
        return false;
    }

    public function pendingTransactionsExists($user_id)
    {
        return Transacties::find()
            ->where(['transacties_user_id' => $user_id])
            ->andWhere(['mollie_status' => self::MOLLIE_STATUS_pending])
            ->andWhere('ISNULL(deleted_at)')
            ->exists();
    }

    public function setParameters()
    {
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
            "amount"       => [
                "currency" => "EUR",
                "value" => '15.00', //round($this->bedrag, 2),
            ],
            "method"       => PaymentMethod::IDEAL,
            "description"  => $this->omschrijving,
            "metadata"     => [
                "transacties_id" => $this->transacties_id,
            ],
            "issuer"       => !empty($this->issuer) ? $this->issuer : null
        ];

        $this->parameters['redirectUrl'] = "https://" . $_ENV['URL'] . "/index.php?r=mollie/return-betaling&transacties_id={$this->transacties_id}";
        $this->parameters['webhookUrl'] = "https://" . $_ENV['URL'] . "/index.php?r=mollie/webhook";
    }

    public function createRecurringPayment()
    {
        if (!isset($this->transacties_user_id)) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
        }

        $user = User::findOne($this->transacties_user_id);
        try {
            $customer = $this->mollie->customers->create([
                "name"  => $user->username,
                "email" => $user->email,
            ]);

            $this->parameters['customerId'] = $customer->id;
            $this->parameters['sequenceType'] = 'first';
            $user->automatische_betaling = true;
            $user->mollie_customer_id = $customer->id;
            $user->mollie_bedrag = $this->bedrag;
            if ($user->save()) {
                return true;
            }
        } catch (Mollie_API_Exception $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }

    public function createPayment()
    {
        try {
            $payment = $this->mollie->payments->create($this->parameters);

            /*
             * In this example we store the order with its payment status in a database.
             */
            $this->setRetrievedMollieStatus($payment->status);
            $this->mollie_id = $payment->id;
            if (!$this->save()) {
                foreach ($this->errors as $key => $error) {
                    echo 'Fout met opslaan: ' . $key . ':' . $error[0];
                }
                return false;
            }
            /*
             * Send the customer off to complete the payment.
             */
            return $payment->getCheckoutUrl();
        } catch (Mollie_API_Exception $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }
}
