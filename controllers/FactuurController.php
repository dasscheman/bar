<?php

namespace app\controllers;

use Yii;
use dektrium\user\filters\AccessRule;
use app\models\Factuur;
use app\models\FactuurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\Turven;
use kartik\mpdf\Pdf;
use app\models\Transacties;

/**
 * FactuurController implements the CRUD actions for Factuur model.
 */
class FactuurController extends Controller
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
                        'allow' => true,
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
     * Lists all Factuur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FactuurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
     * Displays a single Factuur model.
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
     * Creates a new Factuur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $users = User::find()
            ->where('ISNULL(blocked_at)')
            ->all();

        if (empty($users)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Er zijn geen gebruikers die een mail gestuurd kan worden.'));
            return $this->redirect(['index']);
        }
        foreach ($users as $user) {
            if (!$user->getNewAfTransactiesUser()->exists() &&
                !$user->getNewBijTransactiesUser()->exists() &&
                !$user->getNewTurvenUsers()->exists() &&
                !$user->getInvalidTransactionsNotInvoiced()->exists()) {
                continue;
            }

            $factuur = new Factuur();
            // return the pdf output as per the destination setting
            if (!$factuur->createFactuur($user)) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan pdf niet genereren.'));
                return $this->redirect(['index']);
            }

            if (!$factuur->updateAfterCreateFactuur($user)) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Pdf is gegenereerd, maar record kunnen niet geupdate worden.'));
                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionDownload($id)
    {
        $download = Factuur::findOne($id);
        $path=Yii::getAlias('@webroot').'/uploads/facture/'.$download->pdf;
        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        } else {
            throw new NotFoundHttpException("can't find {$download->pdf} file");
        }
    }

    /**
     * Updates an existing Factuur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->factuur_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Factuur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $factuur = $this->findModel($id);
        $filename = Yii::getAlias('@webroot') . '/uploads/facture/' . $factuur->pdf;
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($factuur->getTransacties()->all() as $transactie) {
                $transactie->status = Transacties::STATUS_herberekend;
                $transactie->factuur_id = null;
                if (!$transactie->save()) {
                    foreach ($transactie->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                    $dbTransaction->rollBack();
                    return $this->redirect(['index']);
                }
            }
            foreach ($factuur->getTurvens()->all() as $turf) {
                $turf->status = Turven::STATUS_herberekend;
                $turf->factuur_id = null;
                if (!$turf->save()) {
                    foreach ($turf->errors as $key => $error) {
                        Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                    }
                    $dbTransaction->rollBack();
                    return $this->redirect(['index']);
                }
            }
            $factuur->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            if (!$factuur->save()) {
                foreach ($factuur->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }
                $dbTransaction->rollBack();
                return $this->redirect(['index']);
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Je kunt deze factuur niet verwijderen: ' .  $e));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Factuur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Factuur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Factuur::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
