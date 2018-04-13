<?php

namespace app\controllers;

use Yii;

use app\models\Assortiment;
use app\models\ContactForm;
use app\models\Inkoop;
use app\models\Kosten;
use app\models\LoginForm;
use app\models\Turven;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
                        'actions' => ['logout', 'totaal', 'testmail', 'rendement', 'per-merk'],
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
    public function actionTotaal()
    {
        $i = 0;
        $maanden = [];
        $uitgavenMateriaal = [];
        $series = [];

        $kosteTypes = Kosten::getTypeOptions();
        while ($i < 4) {
            $date = date("Ymd", strtotime("-$i months"));
            $maanden[] = date("M-Y", strtotime("-$i months"));
            $inkomsten[date("M", strtotime("-$i months"))] = (float) Turven::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('year(datum) = year(' . $date . ')')
                    ->sum('totaal_prijs');

            $uitgavenDrank[date("M", strtotime("-$i months"))] = (float) Inkoop::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('year(datum) = year(' . $date . ')')
                    ->andWhere('status = ' . Inkoop::STATUS_verkocht)
                    ->sum('totaal_prijs');

            $afschrijvingDrank[date("M", strtotime("-$i months"))] = (float) Inkoop::find()
                    ->where('month(datum) = month(' . $date . ')')
                    ->andWhere('year(datum) = year(' . $date . ')')
                    ->andWhere('status = ' . Inkoop::STATUS_afgeschreven)
                    ->sum('totaal_prijs');

            foreach ($kosteTypes as $key => $kostenType) {
                $uitgavenMateriaal[$kostenType][date("M", strtotime("-$i months"))] = (float) Kosten::find()
                        ->where('month(datum) = month(' . $date . ')')
                        ->andWhere('year(datum) = year(' . $date . ')')
                        ->andWhere('type = ' . $key)
                        ->sum('prijs');
            }

            $i++;
        }
        $series[] = ['name' => 'Inkomsten', 'data' => array_values($inkomsten), 'stack' => 'inkomsten'];
        $series[] = ['name' => 'Drank Inkoop', 'data' => array_values($uitgavenDrank), 'stack' => 'uitgaven'];
        $series[] = ['name' => 'Drank afgeschreven', 'data' => array_values($afschrijvingDrank), 'stack' => 'uitgaven'];
        foreach ($uitgavenMateriaal as $key => $value) {
            $series[] = ['name' => $key, 'data' => array_values($value), 'stack' => 'uitgaven'];
        }
     
        return $this->render('totaal', [
            'maanden' => $maanden,
            'series' => $series,
        ]);
    }
    /**
       * Displays contact page.
       *
       * @return string
       */
    public function actionPerMerk()
    {
        $i = 0;
        $maanden = [];
        $seriesGeld = [];
        $seriesVolume = [];
        $verkoop = [];
        $inkomsten = [];

        $assortiments = Assortiment::find()
            ->where('merk =:merk')
            ->params([':merk' => Yii::$app->request->get('merk')])
            ->all();

        while ($i < 6) {
            $date = date("Ymd", strtotime("-$i months"));
            $maanden[] = date("M", strtotime("-$i months"));
            foreach ($assortiments as $assortiment) {
                //Financieel overzicht per merk.
                $inkomsten_temp = $assortiment->getSumMonthlyTurven($date);
                if (isset($inkomsten[date("M", strtotime("-$i months"))])) {
                    $inkomsten[date("M", strtotime("-$i months"))] = $inkomsten[date("M", strtotime("-$i months"))] + $inkomsten_temp;
                } else {
                    $inkomsten[date("M", strtotime("-$i months"))] = $inkomsten_temp;
                }

                $uitgaven_temp = $assortiment->getSumMonthlyVerkocht($date);
                if (isset($uitgaven[date("M", strtotime("-$i months"))])) {
                    $uitgaven[date("M", strtotime("-$i months"))] = $uitgaven[date("M", strtotime("-$i months"))] + $uitgaven_temp;
                } else {
                    $uitgaven[date("M", strtotime("-$i months"))] = $uitgaven_temp;
                }

                // Volume verkoop
                if (!$assortiment->change_stock_auto) {
                    $turven_temp = $assortiment->getvolumeMonthlyTurven($date);
                } else {
                    $turven_temp = $assortiment->getCountMonthlyTurven($date);
                }

                if (isset($turven[date("M", strtotime("-$i months"))])) {
                    $turven[date("M", strtotime("-$i months"))] = $turven[date("M", strtotime("-$i months"))] + $turven_temp;
                } else {
                    $turven[date("M", strtotime("-$i months"))] = $turven_temp;
                }
                
                if (!$assortiment->change_stock_auto) {
                    $verkoop_temp = $assortiment->getVolumeMonthlyVerkocht($date);
                } else {
                    $verkoop_temp = $assortiment->getCountMonthlyVerkocht($date);
                }

                if (isset($verkoop[date("M", strtotime("-$i months"))])) {
                    $verkoop[date("M", strtotime("-$i months"))] = $verkoop[date("M", strtotime("-$i months"))] + $verkoop_temp;
                } else {
                    $verkoop[date("M", strtotime("-$i months"))] = $verkoop_temp;
                }

                if (!$assortiment->change_stock_auto) {
                    $verlies_temp = $assortiment->getVolumeMonthlyLoss($date);
                } else {
                    $verlies_temp = $assortiment->getCountMonthlyLoss($date);
                }

                if (isset($verlies[date("M", strtotime("-$i months"))])) {
                    $verlies[date("M", strtotime("-$i months"))] = $verlies[date("M", strtotime("-$i months"))] + $verlies_temp;
                } else {
                    $verlies[date("M", strtotime("-$i months"))] = $verlies_temp;
                }

                if (!$assortiment->change_stock_auto) {
                    $y_axis = 'Liter';
                } else {
                    $y_axis = 'Aantal';
                }
            }
            $i++;
        }
        $seriesGeld[] = ['name' => 'Inkomsten Drank', 'data' => array_values($inkomsten), 'stack' => 'inkomsten'];
        $seriesGeld[] = ['name' => 'Uitgaven Drank', 'data' => array_values($uitgaven), 'stack' => 'uitgaven'];

        $seriesVolume[] = ['name' => 'Turven Drank', 'data' => array_values($turven), 'stack' => 'turven'];
        $seriesVolume[] = ['name' => 'Inkoop Drank', 'data' => array_values($verkoop), 'stack' => 'verkocht'];
        $seriesVolume[] = ['name' => 'Afgeschreven Drank', 'data' => array_values($verlies), 'stack' => 'turven'];
     
        return $this->render('grafieken', [
            'maanden' => $maanden,
            'seriesGeld' => $seriesGeld,
            'seriesVolume' => $seriesVolume,
            'labels' => [
                'titel' => Yii::$app->request->get('merk'),
                'y_axis' => $y_axis
            ]
        ]);
    }

    public function actionRendement()
    {
        $series = [];
        $merken = Assortiment::find()
            ->groupBy('merk')
            ->all();

        $start_date = '20171101';
        while ($start_date < date("Ymd")) {
            $maanden[] = date("Ymd", strtotime($start_date));
            $start_date = date('Ymd', strtotime("+1 months", strtotime($start_date)));
        }
        foreach ($merken as $merk) {
            if ($merk->merk == null) {
                continue;
            }
       
            $assortiments = Assortiment::find()
                    ->where('merk =:merk')
                    ->params([':merk' => $merk->merk])
                    ->all();

            $start_date = '20171101';
            $end_date = date('Ymd', strtotime("+2 months", strtotime($start_date)));

            $turven = [];
            $inkoop = [];
            while ($start_date < date("Ymd")) {
                foreach ($assortiments as $assortiment) {
                    $turven_temp = 0;
                    $inkoop_temp = 0;
                    $turven_temp = $assortiment->getVolumeTurvenPeriod($start_date, $end_date);

                    if (isset($turven[date("M-Y", strtotime($end_date))])) {
                        $turven[date("M-Y", strtotime($end_date))] = $turven[date("M-Y", strtotime($end_date))] + $turven_temp;
                    } else {
                        $turven[date("M-Y", strtotime($end_date))] = $turven_temp;
                    }

                    $inkoop_temp = $assortiment->getVolumeInkoopPeriod($start_date, $end_date);

                    if (isset($inkoop[date("M-Y", strtotime($end_date))])) {
                        $inkoop[date("M-Y", strtotime($end_date))] = $inkoop[date("M-Y", strtotime($end_date))] + $inkoop_temp;
                    } else {
                        $inkoop[date("M-Y", strtotime($end_date))] = $inkoop_temp;
                    }
                }

                $start_date = date('Ymd', strtotime("+1 months", strtotime($start_date)));
                $end_date = date('Ymd', strtotime("+2 months", strtotime($start_date)));
            }
            $rendement = [];
            foreach ($maanden as $maand) {
                $maand_temp = date("M-Y", strtotime($maand));
                if (isset($inkoop[$maand_temp]) && isset($turven[$maand_temp]) &&
                        $inkoop[$maand_temp] != 0 && $turven[$maand_temp] != 0) {
                    $rendement[] = [strtotime($maand_temp)*1000, $turven[$maand_temp] / $inkoop[$maand_temp] * 100];
                }
            }
            $series[] = ['name' => 'Rendement ' . $merk->merk, 'data' => $rendement];

            // Get the year rendement

            $start_year = '20180101';
            $end_year = '20181231';
            $turven_year = 0;
            $inkoop_year = 0;
            foreach ($assortiments as $assortiment) {
                $turven_year_temp = $assortiment->getVolumeTurvenPeriod($start_year, $end_year);
                if (isset($turven_year)) {
                    $turven_year = $turven_year + $turven_year_temp;
                } else {
                    $turven_year = $turven_year_temp;
                }
                
                $inkoop_year_temp = $assortiment->getVolumeInkoopPeriod($start_year, $end_year);

                if (isset($inkoop_year)) {
                    $inkoop_year = $inkoop_year + $inkoop_year_temp;
                } else {
                    $inkoop_year = $inkoop_year_temp;
                }
            }
            if (isset($inkoop_year) && isset($turven_year) &&
                $inkoop_year != 0 && $turven_year != 0) {
                $rendement_year[] = ['name' => 'Rendement 2018 ' . $merk->merk, 'data' => $turven_year / $inkoop_year * 100];
            }
        }
        return $this->render('rendement', [
            'maanden' => $maanden,
            'series' => $series,
            'rendement_year' => $rendement_year
        ]);
    }

    public function actionTestmail()
    {
        $user = User::findOne(Yii::$app->user->id);
        $message = Yii::$app->mailer->compose('mail_test', [
                'user' => $user,
            ])
            ->setFrom('bar@debison.nl')
            ->setTo($user->email)
            ->setSubject('Test mail Bison bar');
        $message->send();


        Yii::$app->session->setFlash('succes', Yii::t('app', 'Test mail verzonden'));

        return $this->redirect(['index']);
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
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
