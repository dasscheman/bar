<?php

namespace app\controllers\user;

use Yii;
use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use dektrium\user\filters\AccessRule;
use yii\helpers\Url;
use app\models\TurvenSearch;
use app\models\TransactiesSearch;
use app\models\FactuurSearch;
use app\models\Factuur;
use app\models\User;

class AdminController extends BaseAdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                // We will override the default rule config with the new AccessRule class
                'only' => ['index', 'update', 'update-profile', 'create', 'info'],
                'rules' => [
                    [
                        'allow' =>  true,
                        'actions' => ['index',  'turven', 'update', 'update-profile', 'create', 'info', 'facturen-view'],
                        'roles' =>  ['admin', 'beheerder'],
                    ],
                    [
                        'allow' => false,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTurven($id)
    {
        Url::remember('', 'actions-redirect');

        $user = $this->findModel($id);

        $searchModel = new TurvenSearch();
        $params = Yii::$app->request->queryParams;
        $params['TurvenSearch']['consumer_user_id'] = $id;
        $dataProvider = $searchModel->search($params);

        return $this->render('_turven', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user
        ]);
    }

    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTransacties($id)
    {
        Url::remember('', 'actions-redirect');

        $user = $this->findModel($id);

        $searchModel = new TransactiesSearch();
        $params = Yii::$app->request->queryParams;
        $params['TransactiesSearch']['transacties_user_id'] = $id;
        $dataProvider = $searchModel->search($params);

        return $this->render('_transacties', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user
        ]);
    }
    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFacturen($id)
    {
        Url::remember('', 'actions-redirect');

        $user = $this->findModel($id);

        $searchModel = new FactuurSearch();
        $params = Yii::$app->request->queryParams;
        $params['FactuurSearch']['ontvanger'] = $id;
        $dataProvider = $searchModel->search($params);

        return $this->render('_facturen', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user
        ]);
    }

    /**
     * Displays a single Factuur model.
     * @param integer $id
     * @return mixed
     */
    public function actionFacturenView($id)
    {
        $factuur = Factuur::findOne($id);
        $user = User::findOne($factuur->ontvanger);

        $searchModelTurven = new TurvenSearch();
        $paramsTurven = Yii::$app->request->queryParams;
        $paramsTurven['TurvenSearch']['factuur_id'] = $id;
        $dataProviderTurven = $searchModelTurven->search($paramsTurven);

        $searchModelTransacties = new TransactiesSearch();
        $paramsTransacties = Yii::$app->request->queryParams;
        $paramsTransacties['TransactiesSearch']['factuur_id'] = $id;
        $dataProviderTransacties = $searchModelTransacties->search($paramsTransacties);


        return $this->render('_facturen_view', [
            'user' => $user,
            'model' => $factuur,
            'searchModelTurven' => $searchModelTurven,
            'dataProviderTurven' => $dataProviderTurven,
            'searchModelTransacties' => $searchModelTransacties,
            'dataProviderTransacties' => $dataProviderTransacties,
        ]);
    }
}
