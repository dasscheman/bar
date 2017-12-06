<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Turven;
use app\models\Inkoop;
use app\models\Assortiment;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'grafieken'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['turven/barinvoer']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionGrafieken()
    {
        $i = 0;
        $maanden = [];
        $inkomsten = [];
        $uitgaven = [];
        $volume_inkoop = [];
        $volume_verkoop = [];

        if (Yii::$app->request->get('assortiment_id') !== NULL) {

            while ($i < 3) {
                $date = date("Ymd", strtotime("-$i months"));
                $maanden[] = date("M", strtotime("-$i months"));
                $inkomsten[date("M", strtotime("-$i months"))] = (float) Turven::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('assortiment_id = ' . Yii::$app->request->get('assortiment_id'))
                    ->sum('totaal_prijs');

                $aantal = (float) Turven::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('assortiment_id = ' . Yii::$app->request->get('assortiment_id'))
                    ->sum('aantal');

                $item = Assortiment::findOne(Yii::$app->request->get('assortiment_id'));
                $volume_verkoop[date("M", strtotime("-$i months"))] = (float) $aantal * (float) $item->volume / (float) 1000;

                $uitgaven[date("M", strtotime("-$i months"))] = (float) Inkoop::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('assortiment_id = ' . Yii::$app->request->get('assortiment_id'))
                    ->andWhere('status = ' . Inkoop::STATUS_verkocht)
                    ->sum('totaal_prijs');

                $volume_inkoop[date("M", strtotime("-$i months"))] = (float) Inkoop::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('assortiment_id = ' . Yii::$app->request->get('assortiment_id'))
                    ->andWhere('status = ' . Inkoop::STATUS_verkocht)
                    ->sum('volume');

                $i++;
            }
        } else {
            while ($i < 3) {
                $date = date("Ymd", strtotime("-$i months"));
                $maanden[] = date("M", strtotime("-$i months"));
                $inkomsten[date("M", strtotime("-$i months"))] = (float) Turven::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->sum('totaal_prijs');


                $uitgaven[date("M", strtotime("-$i months"))] = (float) Inkoop::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('status = ' . Inkoop::STATUS_verkocht)
                    ->sum('totaal_prijs');

                $i++;
            }
        }

        $assortimentItems = Assortiment::findAll(['status' => Assortiment::STATUS_beschikbaar]);

        return $this->render('grafieken', [
            'maanden' => $maanden,
            'inkomsten' => $inkomsten,
            'uitgaven' => $uitgaven,
            'volume_verkoop' => $volume_verkoop,
            'volume_inkoop' => $volume_inkoop,
            'assortimentItems' => $assortimentItems,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
//        dd($exception);
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
