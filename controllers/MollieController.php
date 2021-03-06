<?php

namespace app\controllers;

use Yii;
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
                'only' => ['webhook', 'automatisch-betaling-update', 'automatisch-betaling-annuleren', 'return-betaling', 'betaling'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['automatisch-betaling-update', 'automatisch-betaling-annuleren', 'return-betaling', 'betaling'],
                        'roles' =>  ['onlinebetalen'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['webhook', 'automatisch-betaling-update', 'automatisch-betaling-annuleren', 'return-betaling', 'betaling'],
                        'roles' =>  ['?'],
                    ],
                ],
            ],
        ];
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
                    $user = User::findOne($model->transacties_user_id);
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

        if (isset(Yii::$app->user->id)) {
            $user = User::findOne(Yii::$app->user->id);
        }

        if (!isset($user)) {
            $user = User::findByPayKey(Yii::$app->request->get('pay_key'));
        }

        if (!isset($user)) {
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
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
            throw new NotFoundHttpException('Je bent niet ingelogt of de link uit je email is niet meer geldig.');
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
            switch ($payment->status) {
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
        // 3 seond sleep om zeker te weten dat de webhook eerst is aangeroepen
        // en de status gezet is.
        sleep(3);
        $transactie = Transacties::findOne(Yii::$app->request->get('transacties_id'));
        if (isset($transactie->mollie_status)) {
            switch ($transactie->mollie_status) {
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
        if (isset(Yii::$app->user->id)) {
            $user = User::findOne(Yii::$app->user->id);
        }

        if (!isset($user)) {
            $user = User::findByPayKey(Yii::$app->request->post('pay_key'));
        }

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
}
