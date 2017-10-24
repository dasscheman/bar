<?php

namespace app\controllers;

use Yii;
use dektrium\user\filters\AccessRule;
use app\models\Assortiment;
use app\models\Turven;
use app\models\TurvenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Prijslijst;

/**
 * TurvenController implements the CRUD actions for Turven model.
 */
class TurvenController extends Controller
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
                        'allow' => TRUE,
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
     * Lists all Turven models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TurvenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Turven model.
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
     * Creates a new Turven model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $models = [new Turven()];
        for($i = 1; $i < 5; $i++) {
            $models[] = new Turven();
        }

        if (Turven::loadMultiple($models, Yii::$app->request->post())) {
            $count = 0;
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($models as $model) {
                    if(empty($model->assortiment_id)) {
                        continue;
                    }

                    if($count > 0) {
                        $model->datum = $models[0]->datum;
                        $model->turflijst_id = $models[0]->turflijst_id;
                        $model->consumer_user_id = $models[0]->consumer_user_id;
                        $model->turflijst_id = $models[0]->turflijst_id;
                        $model->status = $models[0]->status;
                    }

                    if(empty($model->datum)) {
                        $prijslijst = Prijslijst::determinePrijslijstTurflijstIdBased($model->assortiment_id, $model->turflijst_id);
                    } else if(empty($model->turflijst_id)) {
                        $prijslijst = Prijslijst::determinePrijslijstDateBased($model->assortiment_id, $model->datum);
                    }

                    if(!$prijslijst) {
                        if(empty($model->turflijst_id)) {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is geen geldige turflijst voor ' . $model->getAssortiment()->one()->name));
                        } else {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is geen geldige prijs voor ' . $model->getAssortiment()->one()->name));
                        }
                        return $this->render('create', [
                            'models' => $models,
                        ]);
                    }

                    $model->totaal_prijs = number_format($model->aantal * $prijslijst->prijs, 2);
                    $model->prijslijst_id = $prijslijst->prijslijst_id;
                    $model->type = Turven::TYPE_turflijst;
                    $model->status = Turven::STATUS_gecontroleerd;

                    if(!$model->save()) {
                        $dbTransaction->rollBack();
                        foreach ($model->errors as $key => $error) {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan turven niet opslaan:' . $error[0]));
                        }
                        return $this->render('create', ['models' => $models]);
                    }
                    $count++;
                }
                $dbTransaction->commit();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze turven niet verwijderen.'));
            }
            return $this->redirect(['index']);
        }
        return $this->render('create', ['models' => $models]);
    }

    /**
     * Updates an existing Turven model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->turven_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Turven model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $factuur = $model->getFactuur()->one();

        if(empty($factuur)) {
            if(!$model->delete()){
                 Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze transactie niet verwijderen.'));
            }
            return $this->redirect(['index']);
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {

            foreach($factuur->getTransacties()->all() as $transactie) {
                $transactie->status = Transacties::STATUS_tercontrole;
                $transactie->factuur_id = NULL;
                if(!$transactie->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }
            foreach($factuur->getTurvens()->all() as $turf) {
                if ($model->turven_id == $turf->turven_id) {
                    // Deze gaan we sowieso verwijderen, ik weet niet of het
                    // goed gaat als dit record dan eerst gewijzigd wordt.
                    continue;
                }
                $turf->status = Turven::STATUS_tercontrole;
                $turf->factuur_id = NULL;
                if(!$turf->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }
            if(!$factuur->delete() || $model->delete()){
                $dbTransaction->rollBack();
                return FALSE;
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze turven niet verwijderen.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Turven model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Turven the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Turven::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
