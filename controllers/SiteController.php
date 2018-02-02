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

        if (Yii::$app->request->get('merk') !== NULL) {

            $assortiments = Assortiment::find()
                    ->where('merk =:merk')
                    ->params([':merk' => Yii::$app->request->get('merk')])
                    ->all();



            while ($i < 4) {
                $date = date("Ymd", strtotime("-$i months"));
                $maanden[] = date("M", strtotime("-$i months"));
//                $volume_verkoop[date("M", strtotime("-$i months"))];
                foreach ($assortiments as $assortiment) {
                    $inkomsten_temp = (float) Turven::find()
                        ->where('month(datum) = month(' . $date . ')')
                        ->andWhere('assortiment_id = ' . $assortiment->assortiment_id)
                        ->sum('totaal_prijs');


                    if(isset($inkomsten[date("M", strtotime("-$i months"))])) {
                        $inkomsten[date("M", strtotime("-$i months"))] = $inkomsten_temp + $inkomsten[date("M", strtotime("-$i months"))];
                    } else {
                        $inkomsten[date("M", strtotime("-$i months"))] = $inkomsten_temp;
                    }

                    $aantal = (float) Turven::find()
                        ->where('month(datum) = month(' . $date . ')')
                        ->andWhere('assortiment_id = ' . $assortiment->assortiment_id)
                        ->sum('aantal');

                    $item = Assortiment::findOne($assortiment->assortiment_id);
                    $volume = (float) $aantal * (float) $item->volume / (float) 1000;

                    if(isset($volume_verkoop[date("M", strtotime("-$i months"))])) {
                        $volume_verkoop[date("M", strtotime("-$i months"))] = $volume + $volume_verkoop[date("M", strtotime("-$i months"))];
                    } else {
                        $volume_verkoop[date("M", strtotime("-$i months"))] = $volume;
                    }

                    $uitgaven_temp = (float) Inkoop::find()
                        ->where('month(datum) = month(' . $date . ')')
                        ->andWhere('assortiment_id = ' . $assortiment->assortiment_id)
                        ->andWhere('status = ' . Inkoop::STATUS_verkocht)
                        ->sum('totaal_prijs');

                    if (isset( $uitgaven[date("M", strtotime("-$i months"))])) {
                        $uitgaven[date("M", strtotime("-$i months"))] = $uitgaven[date("M", strtotime("-$i months"))] + $uitgaven_temp;
                    } else {
                        $uitgaven[date("M", strtotime("-$i months"))] = $uitgaven_temp;
                    }

                    $volume_inkoop_temp = (float) Inkoop::find()
                        ->where('month(datum) = month(' . $date . ')')
                        ->andWhere('assortiment_id = ' . $assortiment->assortiment_id)
                        ->andWhere('status = ' . Inkoop::STATUS_verkocht)
                        ->sum('volume');

                    if(isset($volume_inkoop[date("M", strtotime("-$i months"))])) {
                        $volume_inkoop[date("M", strtotime("-$i months"))] = $volume_inkoop[date("M", strtotime("-$i months"))] + $volume_inkoop_temp;
                    } else {
                        $volume_inkoop[date("M", strtotime("-$i months"))] = $volume_inkoop_temp;
                    }
                }
                $i++;
            }
        } else {
            while ($i < 4) {
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

        $assortimentItems = Assortiment::find()
            ->where(['status' => Assortiment::STATUS_beschikbaar])
            ->groupBy('merk')
            ->all();
        
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
