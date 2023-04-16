<?php

namespace app\controllers;

use app\models\Turven;
use Yii;
use yii\helpers\Url;
use app\models\Transacties;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use yii\web\Controller;
use app\models\Mollie;
use app\models\BetalingType;
use app\models\User;
use app\models\Factuur;
use yii\web\NotFoundHttpException;
use Da\QrCode\QrCode;

/**
 * TransactiesController implements the CRUD actions for Transacties model.
 */
class MollieController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                    'actions' => [
                        'webhook' => ['POST', 'GET'],
                        'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                // We will override the default rule config with the new AccessRule class
                'only' => ['webhook', 'directe-webhook', 'automatisch-betaling-update', 'automatisch-betaling-annuleren', 'return-betaling', 'return-directe-betaling', 'betaling', 'qr-directe-betaling', 'directe-betaling'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['qr-directe-betaling'],
                        'roles' =>  ['admin', 'beheerder'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['webhook', 'directe-webhook', 'automatisch-betaling-update', 'automatisch-betaling-annuleren', 'return-betaling', 'return-directe-betaling', 'betaling', 'directe-betaling'],
                        'roles' =>  ['?'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'webhook' || $action->id === 'webhook-directe-betaling') {
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
        $user = User::findByPayKey(Yii::$app->request->get('pay_key'));

        if (!isset($user)) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
        }

        $model = new Mollie;
        $model->scenario = 'betaling';
        if ($model->load(Yii::$app->request->post())) {
            $betalingType = new BetalingType();
            $model->type_id = $betalingType->getIdealId();
            $model->datum = date('Y-m-d H:i:s');
            $model->status = Transacties::STATUS_ingevoerd;
            if ($model->automatische_betaling) {
                $model->omschrijving = 'Instellen Automatisch'. $model->omschrijving;
            }
            if ($model->save()) {
                $model->setParameters();
                if ($model->automatische_betaling && $model->createRecurringPayment()) {
                    $message = Yii::$app->mailer->compose('mail_incasso_betaling_aangemaakt', [
                        'user' => $user,
                        'transactie' => $model,
                    ])
                    ->setFrom($_ENV['ADMIN_EMAIL'])
                    ->setTo($user->email)
                    ->setSubject('Incasso betaling Bison bar');
                    if (!empty($user->profile->public_email)) {
                        $message->setCc($user->profile->public_email);
                    }
                    $message->send();
                }

                $payment = $model->createPayment();

                if ($payment) {
                    $this->redirect($payment);
                }
            } else {
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
            }
        }

        $this->layout = 'main-fluid';
        $model->transacties_user_id = $user->id;
        $model->omschrijving = 'betaling Bisonbar ' . $user->profile->voornaam . ' '  . $user->profile->achternaam;
        return $this->render('create', [
            'model' => $model,
            'user' => $user
        ]);
    }

    public function actionWebhook()
    {
        $mollie = new Mollie;
        $old_factuur = null;
        if (Yii::$app->request->post('id') === null) {
            throw new NotFoundHttpException('Geen geldig betaal token gevonden.');
        }

        /*
         * Retrieve the payment's current state.
         */
        $payment = $mollie->mollie->payments->get(Yii::$app->request->post('id'));
        $transacties_id = $payment->metadata->transacties_id;
        $model = Transacties::findOne($transacties_id);
        if ($payment->id !== $model->mollie_id) {
            throw new NotFoundHttpException('The requested id does not correspond the database.');
        }
        try {
            // Bewaar een eventuele factuur id.
            if ($model->factuur_id !== null) {
                $old_factuur = $model->factuur_id;
            }
            /*
             * Update the transactie in the database.
             */
            $this->saveStatussen($model, $payment->status);

            if ($old_factuur !== null) {
                $factuur = new Factuur();
                $factuur->deleteFactuur($old_factuur);
            }
            if ($payment->isPaid() === true) {
                $user = User::findOne($model->transacties_user_id);
                $message = Yii::$app->mailer->compose('mail_ontvangen_betaling', [
                        'user' => $user,
                        'transactie' => $model,
                    ])
                    ->setFrom($_ENV['ADMIN_EMAIL'])
                    ->setTo($user->email)
                    ->setSubject('Online betaling Bison bar');
                if (!empty($user->profile->public_email)) {
                    $message->setCc($user->profile->public_email);
                }
                $message->send();
            } elseif ($payment->isOpen() === false) {
                $user = User::findOne($model->transacties_user_id);
                $message = Yii::$app->mailer->compose('mail_mislukte_betaling', [
                        'user' => $user,
                        'transactie' => $model,
                    ])
                    ->setFrom($_ENV['ADMIN_EMAIL'])
                    ->setTo($user->email)
                    ->setSubject('Fout bij online betaling Bison bar');
                if (!empty($user->profile->public_email)) {
                    $message->setCc($user->profile->public_email);
                }
                $message->send();
            }
        } catch (Mollie_API_Exception $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }

    public function actionReturnBetaling()
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
        }

        // 3 seond sleep om zeker te weten dat de webhook eerst is aangeroepen
        // en de status gezet is.
        sleep(3);
        $transactie = Transacties::findOne(Yii::$app->request->get('transacties_id'));
        if (isset($transactie->mollie_status)) {
            $this->setFlashMessage($transactie->mollie_status);
        } else {
            Yii::$app->session->setFlash('warning', 'Ongeldige transactie, neem contact op met de beheerder.');
        }
        if (!isset(Yii::$app->user->id)) {
            Yii::$app->session->setFlash('primary', 'Log in om je overzicht te bekijken.');
        }
        return $this->render('/user/overzicht', ['model' => User::findOne(Yii::$app->user->id)]);
    }

    public function actionAutomatischBetalingUpdate()
    {
        $user = User::findByPayKey(Yii::$app->request->post('pay_key'));

        if (!isset($user)) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
        }

        if (!isset($user->automatische_betaling) ||
            !$user->automatische_betaling) {
            throw new NotFoundHttpException('Computer says no');
        }

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('success', 'Wijziging in bedrag is opgeslagen.');
            $message = Yii::$app->mailer->compose('mail_incasso_betaling_gewijzigd', [
                    'user' => $user,
                ])
                ->setFrom($_ENV['ADMIN_EMAIL'])
                ->setTo($user->email)
                ->setSubject('Wijziging betaling Bison bar');
            if (!empty($user->profile->public_email)) {
                $message->setCc($user->profile->public_email);
            }
            $message->send();

            return $this->render('/user/overzicht', ['model' => $user]);
        } else {
            foreach ($user->errors as $key => $error) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
            }
        }

        $this->layout = 'main-fluid';
        return $this->render('update', [
            'model' => $user
        ]);
    }

    public function actionAutomatischBetalingAnnuleren()
    {
        $user = User::findByPayKey(Yii::$app->request->post('pay_key'));

        if (!isset($user)) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
        }

        if (!isset($user->automatische_betaling) ||
            !$user->automatische_betaling) {
            throw new NotFoundHttpException('Computer says no');
        }

        $user->automatische_betaling = false;
        $user->mollie_customer_id = '';
        $user->mollie_bedrag = null;
        if ($user->save()) {
            Yii::$app->session->setFlash('success', 'Automatisch ophogen is stop gezet.');
            $message = Yii::$app->mailer->compose('mail_incasso_betaling_gestopt', [
                    'user' => $user,
                ])
                ->setFrom($_ENV['ADMIN_EMAIL'])
                ->setTo($user->email)
                ->setSubject('Annulering incasso Bison bar');
            if (!empty($user->profile->public_email)) {
                $message->setCc($user->profile->public_email);
            }
            $message->send();
        } else {
            foreach ($user->errors as $key => $error) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
            }
        }

        return $this->render('/user/overzicht', ['model' => $user]);
    }

    public function actionQrDirecteBetaling($transactie_key) {
        $model = Mollie::findByKey($transactie_key);
        $link = Url::to(['mollie/directe-betaling', 'transactie_key' => $model->transactie_key], true);
        $qrCode = new QrCode($link);

        $qrCode->writeFile('qrcode/'. $model->transactie_key . '.jpg');
        return $this->render('/mollie/qr', ['model' => $model]);
    }

    public function actionDirecteBetaling($transactie_key) {
        $model = Mollie::findByKey($transactie_key);

        if ($model->load(Yii::$app->request->post())) {
            $model->setParameters();
            $model->status = Transacties::STATUS_wacht_op_betaling;
            $model->parameters['redirectUrl'] = "https://" . $_ENV['URL'] . "/mollie/return-directe-betaling?transactie_key={$model->transactie_key}";
            $model->parameters['webhookUrl'] = "https://" . $_ENV['URL'] . "/mollie/webhook-directe-betaling";
            $payment = $model->createPayment();

            if ($payment) {
                $this->redirect($payment);
            }
        }
        $this->layout = 'main-fluid';
        $model->status = Transacties::STATUS_betaling_gestart;
        if (!$model->save()) {
            foreach ($model->errors as $key => $error) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan transactie niet opslaan:' . $error[0]));
            }
        }

        return $this->render('create-directebetaling', [
            'model' => $model
        ]);
    }

    public function actionReturnDirecteBetaling()
    {
        // 3 seond sleep om zeker te weten dat de webhook eerst is aangeroepen
        // en de status gezet is.
        sleep(3);
        $transactie = Transacties::findByKey(Yii::$app->request->get('transactie_key'));
        if (isset($transactie->mollie_status)) {
            $this->setFlashMessage($transactie->mollie_status);
        } else {
            Yii::$app->session->setFlash('warning', 'Ongeldige transactie, neem contact op met de beheerder.');
        }
        return $this->render('/mollie/overzichtdirectebetaling', ['model' => $transactie]);
    }

    public function actionWebhookDirecteBetaling()
    {
        $mollie = new Mollie;
        if (Yii::$app->request->post('id') === null) {
            throw new NotFoundHttpException('Geen geldig betaal token gevonden.');
        }
        /*
         * Retrieve the payment's current state.
         */
        $payment = $mollie->mollie->payments->get(Yii::$app->request->post('id'));
        $transacties_id = $payment->metadata->transacties_id;
        $model = Transacties::findOne($transacties_id);
        if ($payment->id !== $model->mollie_id) {
            throw new NotFoundHttpException('The requested id does not correspond the database.');
        }
        try {
            /*
             * Update the transactie in the database.
             */
            $this->saveStatussen($model, $payment->status);
            if($model->status = Transacties::STATUS_gecontroleerd) {
                foreach($model->turvens as $turf ) {
                    $turf->status = TURVEN::STATUS_gecontroleerd;
                    $turf->save();
                }
            }

        } catch (Mollie_API_Exception $e) {
            $model->sendErrorReport($e->getMessage());
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }

    private function setFlashMessage($mollie_status)
    {
        switch ($mollie_status) {
            case Transacties::MOLLIE_STATUS_open:
                Yii::$app->session->setFlash('warning', 'Je betaling wordt verwerkt.');
                break;
            case Transacties::MOLLIE_STATUS_cancelled:
                Yii::$app->session->setFlash('error', 'Je betaling is geannuleerd.');
                break;
            case Transacties::MOLLIE_STATUS_expired:
                Yii::$app->session->setFlash('error', 'Betalingssessie is verlopen.');
                break;
            case Transacties::MOLLIE_STATUS_failed:
                Yii::$app->session->setFlash('error', 'Betaling is mislukt.');
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
    }

    private function saveStatussen(&$model, $paymentStatus)
    {
        switch ($paymentStatus) {
            case 'open':
                $model->mollie_status = Transacties::MOLLIE_STATUS_open;
                $model->status = Transacties::STATUS_ingevoerd;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
            case 'canceled':
                $model->mollie_status = Transacties::MOLLIE_STATUS_cancelled;
                $model->status = Transacties::STATUS_geannuleerd;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
            case 'expired':
                $model->mollie_status = Transacties::MOLLIE_STATUS_expired;
                $model->status = Transacties::STATUS_ongeldig;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
            case 'failed':
                $model->mollie_status = Transacties::MOLLIE_STATUS_failed;
                $model->status = Transacties::STATUS_ongeldig;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
            case 'pending':
                $model->mollie_status = Transacties::MOLLIE_STATUS_pending;
                $model->status = Transacties::STATUS_ingevoerd;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
            case 'paid':
                $model->mollie_status = Transacties::MOLLIE_STATUS_paid;
                $model->status = Transacties::STATUS_gecontroleerd;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
            case 'refunded':
                $model->mollie_status = Transacties::MOLLIE_STATUS_refunded;
                $model->status = Transacties::STATUS_teruggestord;
                $model->factuur_id = null;
                $model->deleted_at = null;
                break;
        }
        if (!$model->save()) {
            $model->sendErrorReport();
        }
    }
}
