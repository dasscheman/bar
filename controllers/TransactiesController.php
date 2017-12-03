<?php

namespace app\controllers;

use Yii;
use dektrium\user\filters\AccessRule;
use app\models\Transacties;
use app\models\TransactiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Bonnen;
use yii\web\UploadedFile;

/**
 * TransactiesController implements the CRUD actions for Transacties model.
 */
class TransactiesController extends Controller
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
                'only' => ['index', 'view', 'create', 'create-declaraties', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => TRUE,
                        'actions' => ['index', 'delete', 'create', 'create-declaraties', 'update', 'view'],
                        'roles' =>  ['admin', 'beheerder'],
                    ],
//                    [
//                        'allow' => FALSE,  // deny all users
//                        'roles'=> ['*'],
//                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Transacties models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transacties model.
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
     * Creates a new Transacties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transacties();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->transacties_id]);
        } else {
            $model->datum = date("Y-m-d");
            return $this->render('create', [
                'modelTransacties' => $model,
            ]);
        }
    }

    /**
     * Creates a new Transacties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateDeclaratie()
    {
        $modelTransacties = new Transacties();
        $modelBonnen = new Bonnen();

        if ($modelTransacties->load(Yii::$app->request->post())){
            $image = UploadedFile::getInstance($modelBonnen, 'image_temp');
            if(!empty($image)) {
                $modelBonnen->load(Yii::$app->request->post());
                // store the source file name
                $modelBonnen->image = date('Y-m-d H:i:s') . '-' . $image->name;
                $modelBonnen->omschrijving = $modelTransacties->omschrijving;
                $modelBonnen->type = Bonnen::TYPE_declaratie;
                $modelBonnen->datum = $modelTransacties->datum;
                $modelBonnen->bedrag = $modelTransacties->bedrag;

                $path = Yii::$app->params['bonnen_path'] . $modelBonnen->image;
                if($modelBonnen->save()){
                    $image->saveAs($path);
                } else {
                    foreach ($modelBonnen->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                }
                $modelTransacties->bon_id = $modelBonnen->bon_id;
            }
            if ($modelTransacties->save()) {
                return $this->redirect(['view', 'id' => $modelTransacties->transacties_id]);
            } else {
                foreach ($modelBonnen->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
            }
        }

        $modelTransacties->datum = date("Y-m-d");
        return $this->render('create', [
            'modelTransacties' => $modelTransacties,
            'modelBonnen' => $modelBonnen
        ]);
    }

    /**
     * Updates an existing Transacties model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelTransacties = $this->findModel($id);
        $modelBonnen = $modelTransacties->bon;

        if ($modelTransacties->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($modelBonnen, 'image_temp');
            if(!empty($image)) {
                // store the source file name
                $modelBonnen->image = date('Y-m-d H:i:s') . '-' . $image->name ;
                $modelBonnen->omschrijving = $modelTransacties->omschrijving;
                $modelBonnen->type = Bonnen::TYPE_declaratie;
                $modelBonnen->datum = $modelTransacties->datum;
                $modelBonnen->bedrag = $modelTransacties->bedrag;

                $path = Yii::$app->params['bonnen_path'] . $modelBonnen->image;
                if($modelBonnen->save()){
                    $image->saveAs($path);
                } else {
                    foreach ($modelBonnen->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ': ' . $error[0]));
                    }
                }
                $modelTransacties->bon_id = $modelBonnen->bon_id;
            }
            if ($modelTransacties->save()) {
                return $this->redirect(['view', 'id' => $modelTransacties->transacties_id]);
            } else {
                foreach ($modelBonnen->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ': ' . $error[0]));
                }
            }
        }
         
        return $this->render('update', [
            'modelTransacties' => $modelTransacties,
            'modelBonnen' => $modelBonnen
        ]);
    }

    /**
     * Deletes an existing Transacties model.
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
                if ($model->transacties_id == $transactie->transacties_id) {
                    // Deze gaan we sowieso verwijderen, ik weet niet of het
                    // goed gaat als dit record dan eerst gewijzigd wordt.
                    continue;
                }
                $transactie->status = Transacties::STATUS_tercontrole;
                $transactie->factuur_id = NULL;
                if(!$transactie->save()) {
                    $dbTransaction->rollBack();
                    return FALSE;
                }
            }
            foreach($factuur->getTurvens()->all() as $turf) {
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
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze transactie niet verwijderen.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Transacties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transacties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transacties::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
