<?php

namespace app\controllers;

use Yii;
use dektrium\user\filters\AccessRule;
use app\models\Assortiment;
use app\models\AssortimentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PrijslijstSearch;
use app\models\InkoopSearch;

/**
 * AssortimentController implements the CRUD actions for Assortiment model.
 */
class AssortimentController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                // We will override the default rule config with the new AccessRule class
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' =>  true,
                        'actions' => ['index', 'delete', 'create', 'update', 'view'],
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
     * Lists all Assortiment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AssortimentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('beheer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Assortiment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $assortiment = $this->findModel($id);

        $searchModelPrijslijst = new PrijslijstSearch();
        $paramsPrijslijst['PrijslijstSearch']['assortiment_id'] = $id;
        $dataProviderPrijslijst = $searchModelPrijslijst->search($paramsPrijslijst);

        $searchModelInkoop = new InkoopSearch();
        $paramsInkoop = Yii::$app->request->queryParams;
        $paramsInkoop['InkoopSearch']['assortiment_id'] = $id;
        $dataProviderInkoopAll = $searchModelInkoop->search($paramsInkoop);
        $dataProviderInkoopVoorraad = $searchModelInkoop->searchActueel($paramsInkoop);

        $this->layout = 'main-fluid';
        return $this->render('view', [
            'model' => $assortiment,
            'dataProviderPrijslijst' => $dataProviderPrijslijst,
            'searchModelPrijslijst' => $searchModelPrijslijst,
            'searchModelInkoop' => $searchModelInkoop,
            'dataProviderInkoopAll' => $dataProviderInkoopAll,
            'dataProviderInkoopVoorraad' => $dataProviderInkoopVoorraad
        ]);
    }

    /**
     * Creates a new Assortiment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Assortiment();
        $this->layout = 'main-fluid';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->assortiment_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Assortiment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->layout = 'main-fluid';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->assortiment_id]);
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Assortiment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            $model->delete();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt dit item uit het assoritment niet verwijderen.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assortiment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Assortiment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assortiment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Searches for rules.
     *
     * @param  string|null $q
     * @return array
     */
    public function actionSearch($q = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return ['results' => $this->getStatusOptions()];
    }
}
