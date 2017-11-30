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
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' =>  true,
                        'actions' => ['index', 'delete', 'create', 'update', 'view'],
                        'roles' =>  ['admin', 'beheerder'],
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

        return $this->render('index', [
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

        if ($model->load(Yii::$app->request->post())) {
            $bon = Bonnen::findOne($model->bon_id);
            $model->datum = $bon->datum;
            if(!$model->save()){
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
            } else {
                return $this->redirect(['view', 'id' => $model->inkoop_id]);
            }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->inkoop_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
