<?php

namespace app\controllers;

use Yii;
use app\models\Transacties;
use app\controllers\TransactiesController;
use app\models\Mollie;
use app\models\BetalingType;
use Mollie_API_Object_Method;
use app\models\User;

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
        $behaviors['access']['only'][] = 'link-betaling';
        $behaviors['access']['only'][] = 'webhook';
        $behaviors['access']['only'][] = 'return-betaling';
        $behaviors['access']['rules'][] =
            [
                'allow' => TRUE,
                'actions' => ['betaling', 'link-betaling', 'webhook', 'return-betaling'],
                'roles' =>  ['admin', 'beheerder'],
            ];
        return $behaviors;
    }


    /**
     * Creates a new Transacties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBetaling()
    {
        $mollie = new Mollie;
        $model = new Transacties();

        if ($model->load(Yii::$app->request->post())) {
            $model->transacties_user_id = Yii::$app->user->id;
            $model->type_id = BetalingType::getIdealId();
            $model->datum = date("Y-m-d");
            $model->status = Transacties::STATUS_ingevoerd;
            if($model->save()) {
                try
                {
                    $order_id = time();
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
                    $payment = $mollie->payments->create(array(
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
        }
        
        return $this->render('create', [
            'modelTransacties' => $model,
            'mollie' => $mollie,
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
            $payment = $mollie->payments->get(Yii::$app->request->post('transacties_id'));
            $transacties_id = $payment->metadata->transacties_id;
            $model = Transacties::findOne($transacties_id);

            /*
             * Update the transactie in the database.
             */
            $model->setRetrievedMollieStatus($payment->status);
            $model->save();
            if ($payment->isPaid() === TRUE)
            {
                $user = User::findOne($model->transacties_user_id);
                $message = Yii::$app->mailer->compose('mail_ontvangen_betaling', [
                        'usersProfiel' => $user->profile,
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
                        'usersProfiel' => $user->profile,
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
                Yii::$app->session->setFlash('succes', 'De betaling is met succes verwerkt.');
                break;
            case Transacties::MOLLIE_STATUS_refunded:
                Yii::$app->session->setFlash('info', 'Betaling is teruggestord.');
                break;
            default:
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
        }

        return $this->render('/user/overzicht', ['model' => User::findOne(Yii::$app->user->id)]);
    }
}
