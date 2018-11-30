<?php

namespace app\controllers;

use Yii;
use dektrium\user\filters\AccessRule;
use app\models\BetalingType;
use app\models\Bonnen;
use app\models\RelatedTransacties;
use app\models\Transacties;
use app\models\TransactiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
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
//                'only' => ['index', 'view', 'create', 'create-declaraties', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'delete', 'create', 'update', 'view', 'bank'],
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
     * Lists all Transacties models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactiesSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('beheer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Transacties models.
     * @return mixed
     */
    public function actionBank()
    {
        $searchModel = new TransactiesSearch();

        $dataProvider = $searchModel->searchBank(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('beheer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * If "dektrium/yii2-rbac" extension is installed, this page displays form
     * where user can assign multiple auth items to user.
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAssignments($id)
    {
        if (!isset(\Yii::$app->extensions['dektrium/yii2-rbac'])) {
            throw new NotFoundHttpException();
        }
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);

        return $this->render('_assignments', [
            'user' => $user,
        ]);
    }

    /**
     * Displays a single Transacties model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = 'main-fluid';
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
        $this->layout = 'main-fluid';
        if ($model->load(Yii::$app->request->post())) {
            $model->status = Transacties::STATUS_gecontroleerd;
            switch (Yii::$app->request->get('type')) {
                case 'pin':
                    $model->type_id = BetalingType::getPinId();
                    $redirect = ['transacties/bank'];
                    break;
                case 'bankbij_gebruiker':
                    $model->type_id = BetalingType::getBankBijId();
                    break;
                case 'izettle_invoer':
                    $model->type_id = BetalingType::getIzettleInvoerId();
                    break;
                case 'statiegeld':
                    $model->type_id = BetalingType::getStatiegeldId();
                    break;
                case 'declaratie_invoer':
                    $model->type_id = BetalingType::getDeclaratieInvoerId();
                    break;
                case 'declaratie_uitbetaling':
                    $model->type_id = BetalingType::getDeclaratieUitbetaalsId();
                    break;
                case 'izettle_uitbetaling':
                    $model->type_id = BetalingType::getIzettleUitbetalingId();
                    break;
                case 'mollie_uitbetaling':
                    $model->type_id = BetalingType::getMollieUitbetalingId();
                    break;
                case 'izettle_kosten':
                    $model->type_id = BetalingType::getIzettleKosotenId();
                    break;
                case 'ing_kosten':
                    $model->type_id = BetalingType::getIngKostenId();
                    break;
                case 'mollie_kosten':
                    $model->type_id = BetalingType::getMollieKostenId();
                    break;
            }

            if($model->transacties_user_id !== null) {
                $model->omschrijving = BetalingType::getOmschrijving($model->type_id) . ' ' .
                $model->getTransactiesUser()->one()->getProfile()->one()->voornaam . ' ' .
                $model->getTransactiesUser()->one()->getProfile()->one()->achternaam;
            }

            if ($model->save()) {
                $modelBon = new Bonnen();


                if ($modelBon->load(Yii::$app->request->post())) {

                    // get the uploaded file instance. for multiple file uploads
                    // the following data will return an array
                    $image = UploadedFile::getInstance($modelBon, 'image_temp');
                    // store the source file name
                    $modelBon->image = date('Y-m-d H:i:s') . '-' . $image->name;
                    $modelBon->bedrag = $model->bedrag;
                    $modelBon->datum = $model->datum;
                    $modelBon->type = Bonnen::TYPE_pin_betaling;
                    $modelBon->omschrijving = $model->omschrijving;
                    $path = Yii::$app->params['bonnen_path'] . $modelBon->image;
                    if ($modelBon->save()) {
                        $image->saveAs($path);
                        $model->bon_id = $modelBon->bon_id;
                        $model->save();
                    } else {
                        foreach ($modelBon->errors as $key => $error) {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan van de Bon: ' . $key . ':' . $error[0]));
                        }
                    }
                }
                if (isset(Yii::$app->request->post('Transacties')['all_related_transactions'])) {
                    Transacties::addRelatedTransactions($model->transacties_id, Yii::$app->request->post('Transacties')['all_related_transactions']);
                }
                if(!isset($redirect)) {
                    $redirect = ['view', 'id' => $model->transacties_id];
                }
            } else {
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
                return $this->render('create', [
                    'modelTransacties' => $model,
                    'type' => Yii::$app->request->get()
                ]);
            }
            return $this->redirect($redirect);
        }

        $modelBonnen = new Bonnen();
        $model->datum = date("Y-m-d");
        return $this->render('create', [
            'modelTransacties' => $model,
            'modelBonnen' => $modelBonnen,
            'type' => Yii::$app->request->get()
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
        if($modelBonnen == null) {
            $modelBonnen =new Bonnen;
        }
        $modelTransacties->setAllRelatedTransactions();
        $this->layout = 'main-fluid';
        if ($modelTransacties->load(Yii::$app->request->post())) {
            $image = null;
            if ($modelBonnen !== null) {
                $image = UploadedFile::getInstance($modelBonnen, 'image_temp');
            }
            if (!empty($image)) {
                // store the source file name
                $modelBonnen->image = date('Y-m-d H:i:s') . '-' . $image->name ;
                $modelBonnen->omschrijving = $modelTransacties->omschrijving;
                $modelBonnen->type = Bonnen::TYPE_declaratie;
                $modelBonnen->datum = $modelTransacties->datum;
                $modelBonnen->bedrag = $modelTransacties->bedrag;

                $path = Yii::$app->params['bonnen_path'] . $modelBonnen->image;
                if ($modelBonnen->save()) {
                    $image->saveAs($path);
                } else {
                    foreach ($modelBonnen->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ': ' . $error[0]));
                    }
                }
                $modelTransacties->bon_id = $modelBonnen->bon_id;
            }
            if ($modelTransacties->save()) {
                if (isset(Yii::$app->request->post('Transacties')['all_related_transactions'])) {
                    Transacties::addRelatedTransactions($modelTransacties->transacties_id, Yii::$app->request->post('Transacties')['all_related_transactions']);
                }
                return $this->redirect(['view', 'id' => $modelTransacties->transacties_id]);
            } else {
                foreach ($modelTransacties->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
            }
        }

        $this->layout = 'main-fluid';
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
        if (empty($factuur)) {
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                $relatedModels = RelatedTransacties::find()
                        ->where('parent_transacties_id =:transacties_id')
                        ->orWhere('child_transacties_id =:transacties_id')
                        ->params([':transacties_id' => $model->transacties_id])
                        ->all();

                foreach ($relatedModels as $relatedModel) {
                    if (!$relatedModel->delete()) {
                        $dbTransaction->rollBack();

                        foreach ($relatedModel->errors as $key => $error) {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                        }
                        return $this->redirect(['index']);
                    }
                }

                $model->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
                if (!$model->save()) {
                    $dbTransaction->rollBack();
                    foreach ($model->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                    return $this->redirect(['index']);
                }
                $dbTransaction->commit();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze transactie niet verwijderen: ' . $e));
            }
            return $this->redirect(['index']);
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($factuur->getTransacties()->all() as $transactie) {
                if ($model->transacties_id == $transactie->transacties_id) {
                    // Deze gaan we sowieso verwijderen, ik weet niet of het
                    // goed gaat als dit record dan eerst gewijzigd wordt.
                    continue;
                }
                $transactie->status = Transacties::STATUS_herberekend;
                $transactie->factuur_id = null;
                if (!$transactie->save()) {
                    $dbTransaction->rollBack();
                    foreach ($transactie->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                    return $this->redirect(['index']);
                }
            }
            foreach ($factuur->getTurvens()->all() as $turf) {
                $turf->status = Turven::STATUS_herberekend;
                $turf->factuur_id = null;
                if (!$turf->save()) {
                    $dbTransaction->rollBack();
                    foreach ($turf->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                    return $this->redirect(['index']);
                }
            }

            $relatedModels = RelatedTransacties::find()
                        ->where('parent_transacties_id =:transacties_id')
                        ->orWhere('child_transacties_id =:transacties_id')
                        ->params([':transacties_id' => $model->transacties_id])
                        ->all();
            foreach ($relatedModels as $relatedModel) {
                if (!$relatedModel->delete()) {
                    $dbTransaction->rollBack();
                    foreach ($relatedModel->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                    return $this->redirect(['index']);
                }
            }

            $factuur->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            if (!$factuur->save()) {
                $dbTransaction->rollBack();
                foreach ($factuur->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
                return $this->redirect(['index']);
            }

            $model->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            if (!$model->save()) {
                $dbTransaction->rollBack();
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
                return $this->redirect(['index']);
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze transactie niet verwijderen: ' . $e));
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
            $model->setAllRelatedTransactions();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
