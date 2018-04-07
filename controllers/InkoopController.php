<?php

namespace app\controllers;

use Yii;
use app\models\Inkoop;
use app\models\InkoopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use app\models\Bonnen;

/**
 * InkoopController implements the CRUD actions for Inkoop model.
 */
class InkoopController extends Controller
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
                'only' => ['index', 'index-actueel', 'nieuw-geopend', 'overzicht-actueel', 'view', 'create', 'update', 'verbruikt', 'afgeschreven', 'delete'],
                'rules' => [
                    [
                        'allow' =>  true,
                        'actions' => ['index', 'index-actueel', 'delete', 'create', 'update', 'verbruikt', 'afgeschreven', 'view'],
                        'roles' =>  ['admin', 'beheerder'],
                    ],
                    [
                        'allow' => TRUE,
                        'actions' => ['overzicht-actueel', 'nieuw-geopend'],
                        'roles' =>  ['gebruiker'],
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Inkoop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InkoopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Inkoop models.
     * @return mixed
     */
    public function actionIndexActueel()
    {
        $searchModel = new InkoopSearch();
        $dataProvider = $searchModel->searchActueel(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('index-actueel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all Inkoop models.
     * @return mixed
     */
    public function actionOverzichtActueel()
    {
        $searchModel = new InkoopSearch();
        $dataProvider = $searchModel->searchActueelOverview(Yii::$app->request->queryParams);

        Yii::$app->session->setFlash('warning', Yii::t('app', 'Als je een nieuwe fles of fust open maakt dan kun je dat hier invoeren door het item aan te klikken. '
                . 'Dit is niet nodig voor items die per stuk verkocht worden, zoals flesjes bier.'));
        return $this->render('overzicht-actueel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Inkoop model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Inkoop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Inkoop();

        $this->layout = 'main-fluid';
        if ($model->load(Yii::$app->request->post())) {
            $bon = Bonnen::findOne($model->bon_id);
            $model->datum = $bon->datum;
            $count = $model->aantal;
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                for ($i = 1; $i <= $count; $i++) {

                    $modelTemp = new Inkoop();
                    $modelTemp->attributes = $model->attributes;
                    $modelTemp->aantal = 1;
                    $modelTemp->status = Inkoop::STATUS_voorraad;
                    if(!$modelTemp->save()){
                        foreach ($modelTemp->errors as $key => $error) {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                        }

                        $dbTransaction->rollBack();
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }
                $dbTransaction->commit();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze inkoop niet opslaan'));
            }
            $searchModel = new InkoopSearch();
            $dataProvider = $searchModel->searchActueel(Yii::$app->request->queryParams);

            return $this->render('index-actueel', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Inkoop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->layout = 'main-fluid';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->inkoop_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Inkoop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAfschrijven($id)
    {
        $model = $this->findModel($id);
        $model->status = Inkoop::STATUS_afgeschreven;
        $model->datum = date('Y-m-d H:i:s');
        if(!$model->save()){
            foreach ($model->errors as $key => $error) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
            }
        }
        $searchModel = new InkoopSearch();
        $dataProvider = $searchModel->searchActueel(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('index-actueel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Inkoop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionVerbruikt($id)
    {
        $model = $this->findModel($id);
        $model->status = Inkoop::STATUS_verkocht;
        $model->datum = date('Y-m-d H:i:s');
        if(!$model->save()){
            foreach ($model->errors as $key => $error) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
            }
        }
        $searchModel = new InkoopSearch();
        $dataProvider = $searchModel->searchActueel(Yii::$app->request->queryParams);

        return $this->render('index-actueel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Inkoop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNieuwGeopend($assortiment_id, $omschrijving)
    {
        Inkoop::voorraadBijWerken($assortiment_id, 1, Inkoop::STATUS_verkocht, $omschrijving);
        
        $searchModel = new InkoopSearch();
        $dataProvider = $searchModel->searchActueelOverview(Yii::$app->request->queryParams);

        Yii::$app->session->setFlash('success', Yii::t('app', 'Item is uit de voorraad gehaald.'));
        return $this->render('overzicht-actueel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Inkoop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Inkoop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inkoop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inkoop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
