<?php

namespace app\controllers;

use Yii;
use app\models\Transacties;
use app\controllers\TransactiesController;
use app\models\Mollie;
use app\models\BetalingType;
use Mollie_API_Object_Method;
use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * TransactiesController implements the CRUD actions for Transacties model.
 */
class MollieController extends TransactiesController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['only'][] = 'betaling';
        $behaviors['access']['only'][] = 'webhook';
        $behaviors['access']['only'][] = 'return-betaling';
        $behaviors['access']['rules'][] =
            [
                'allow' => TRUE,
                'actions' => [],
                'roles' =>  ['admin', 'beheerder'],
            ];

        $behaviors['access']['rules'][] =
            [
                'actions' => ['webhook', 'betaling', 'return-betaling'],
                'allow' => true,
            ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        if ($action->id === 'webhook') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Creates a new Transacties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBetaling()
    {
        $model = new Mollie;
        
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findOne(Yii::$app->request->post('user_id'));

            $model->transacties_user_id = $user->id;
            $model->type_id = BetalingType::getIdealId();
            $model->datum = date('Y-m-d H:i:s');
            $model->status = Transacties::STATUS_ingevoerd;
            if($model->save()) {
                try
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
                    $payment = $model->mollie->payments->create(array(
                        "amount"       => $model->bedrag,
                        "method"       => Mollie_API_Object_Method::IDEAL,
                        "description"  => $model->omschrijving,
                        "redirectUrl"  => "https://popupbar.biologenkantoor.nl/index.php?r=mollie/return-betaling&transacties_id={$model->transacties_id}",
                        "webhookUrl"   => "https://popupbar.biologenkantoor.nl/index.php?r=mollie/webhook",
//                        "redirectUrl"  => "https://bar.debison.nl/index.php?r=mollie/return?transacties_id={$model->transacties_id}",
//                        "webhookUrl"   => "https://bar.debison.nl/index.php?r=mollie/webhook",
                        "metadata"     => array(
                            "transacties_id" => $model->transacties_id,
                        ),
                        "issuer"       => !empty($model->issuer) ? $model->issuer : NULL
                    ));
                    /*
                     * In this example we store the order with its payment status in a database.
                     */
                    $model->setRetrievedMollieStatus($payment->status);
                    $model->save();
                    
                    /*
                     * Send the customer off to complete the payment.
                     */
                    $this->redirect($payment->getPaymentUrl());
                    
                }
                catch (Mollie_API_Exception $e)
                {
                    echo "API call failed: " . htmlspecialchars($e->getMessage());
                }

            } else {
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
            }
        } else {
            if(isset(Yii::$app->user->id)) {
                $user = User::findOne(Yii::$app->user->id);
            }
            if (!isset($user)) {
                $user = User::findByPayKey(Yii::$app->request->get('pay_key'));
            }
            if (!isset($user)) {
                throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'user' => $user
        ]);
    }

    public function actionWebhook()
    {
        $mollie = new Mollie;
        try
        {
            /*
             * Retrieve the payment's current state.
             */
            $payment = $mollie->mollie->payments->get(Yii::$app->request->post('id'));
            $transacties_id = $payment->metadata->transacties_id;
            $model = Transacties::findOne($transacties_id);

            /*
             * Update the transactie in the database.
             */
            switch ($payment->status) {
                case 'open':
                    $model->mollie_status = Transacties::MOLLIE_STATUS_open;
                    $model->status = Transacties::STATUS_ingevoerd;
                    break;
                case 'cancelled':
                    $model->mollie_status = Transacties::MOLLIE_STATUS_cancelled;
                    $model->status = Transacties::STATUS_geannuleerd;
                    break;
                case 'expired':
                    $model->mollie_status = Transacties::MOLLIE_STATUS_expired;
                    $model->status = Transacties::STATUS_ongeldig;
                    break;
                case 'failed':
                    $model->mollie_status = Transacties::MOLLIE_STATUS_failed;
                    $model->status = Transacties::STATUS_ongeldig;
                    break;
                case 'paid':
                    $model->mollie_status = Transacties::MOLLIE_STATUS_paid;
                    $model->status = Transacties::STATUS_gecontroleerd;
                    break;
                case 'refunded':
                    $model->mollie_status = Transacties::MOLLIE_STATUS_refunded;
                    $model->status = Transacties::STATUS_gecontroleerd;
                    $model->type_id = BetalingType::getIdealTerugbetalingId();
                    break;
            }
            $model->save();
            if ($payment->isPaid() === TRUE)
            {

                $user = User::findOne($model->transacties_user_id);
                $message = Yii::$app->mailer->compose('mail_ontvangen_betaling', [
                        'user' => $user,
                    ])
                    ->setFrom('bar@debison.nl')
                    ->setTo($user->email)
                    ->setBcc('daan@biologenkantoor.nl')
                    ->setSubject('Online betaling Bison bar');
                if(!empty($user->profile->public_email)) {
                    $message->setCc($user->profile->public_email);
                }
                $message->send();
            } elseif ($payment->isOpen() === FALSE) {
                $user = User::findOne($model->transacties_user_id);
                $message = Yii::$app->mailer->compose('mail_mislukte_betaling', [
                        'user' => $user,
                    ])
                    ->setFrom('bar@debison.nl')
                    ->setTo($user->email)
                    ->setBcc('daan@biologenkantoor.nl')
                    ->setSubject('Online betaling Bison bar');
                if(!empty($user->profile->public_email)) {
                    $message->setCc($user->profile->public_email);
                }
                $message->send();
            }
        }
        catch (Mollie_API_Exception $e)
        {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }

    public function actionReturnBetaling()
    {
        $transactie = Transacties::findOne(Yii::$app->request->get('transacties_id'));
        switch ($transactie->mollie_status) {
            case Transacties::MOLLIE_STATUS_open:
                Yii::$app->session->setFlash('warning', 'Je betaling wordt verwerkt.');
                break;
            case Transacties::MOLLIE_STATUS_cancelled:
                Yii::$app->session->setFlash('danger', 'Je betaling is geannuleerd.');
                break;
            case Transacties::MOLLIE_STATUS_expired:
                Yii::$app->session->setFlash('danger', 'Betalingssessie is verlopen.');
                break;
            case Transacties::MOLLIE_STATUS_failed:
                Yii::$app->session->setFlash('danger', 'Betaling is mislukt.');
                break;
            case Transacties::MOLLIE_STATUS_paid:
                Yii::$app->session->setFlash('success', 'De betaling is met succes verwerkt.');
                break;
            case Transacties::MOLLIE_STATUS_refunded:
                Yii::$app->session->setFlash('info', 'Betaling is teruggestord.');
                break;
            default:
                Yii::$app->session->setFlash('warning', 'Ongeldige transactie, neem contact op met de beheerder.');
        }
        if (!isset(Yii::$app->user->id)) {
             Yii::$app->session->setFlash('primary', 'Log in om je overzicht te bekijken.');
        }
        return $this->render('/user/overzicht', ['model' => User::findOne(Yii::$app->user->id)]);
    }
}
