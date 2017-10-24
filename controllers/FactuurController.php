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
     * Lists all Factuur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FactuurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Factuur model.
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
     * Creates a new Factuur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $users = User::find()
            ->where('ISNULL(blocked_at)')
            ->all();

        if(empty($users)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Er zijn geen gebruikers die een mail gestuurd kan worden.'));
            return $this->redirect(['index']);
        }
        foreach ($users as $user) {
            if (!$user->getNewAfTransactiesUser()->exists() && !$user->getNewBijTransactiesUser()->exists() && !$user->getNewTurvenUsers()->exists()) {
                continue;
            }
            $factuur = new Factuur;
            $factuur->setNewFactuurId();
            $factuur->setNewFactuurName($user->username . '_' . $factuur->factuur_id);

            $new_bij_transacties = $user->getNewBijTransactiesUser()->all();
            $new_af_transacties = $user->getNewAfTransactiesUser()->all();
            $new_turven = $user->getNewTurvenUsers()->all();
            $sum_new_bij_transacties = $user->getSumNewBijTransactiesUser();
            $sum_new_af_transacties = $user->getSumNewAfTransactiesUser();
            $sum_new_turven = $user->getSumNewTurvenUsers();

            $vorig_openstaand =  $user->getSumOldBijTransactiesUser() - $user->getSumOldTurvenUsers() - $user->getSumOldAfTransactiesUser();
            $nieuw_openstaand = $vorig_openstaand - $sum_new_turven + $sum_new_bij_transacties - $sum_new_af_transacties;

            $content = $this->renderPartial('factuur_template',
                [
                    'user' => $user,
                    'new_bij_transacties' => $new_bij_transacties,
                    'new_af_transacties' => $new_af_transacties,
                    'new_turven' => $new_turven,
                    'sum_new_bij_transacties' => $sum_new_bij_transacties,
                    'sum_new_af_transacties' => $sum_new_af_transacties,
                    'sum_new_turven' => $sum_new_turven,
                    'vorig_openstaand' => $vorig_openstaand,
                    'nieuw_openstaand' => $nieuw_openstaand
                ]);

            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
                // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'marginLeft' => 20,
                'marginRight' => 15,
                'marginTop' => 48,
                'marginBottom' => 25,
                'marginHeader' => 10,
                'marginFooter' => 10,
                'defaultFont' => 'arial',
                'filename' => 'uploads/facture/'. $factuur->naam . '.pdf',
                // portrait orientation
                'orientation' => Pdf::FORMAT_A4,
                // stream to browser inline
//                    'destination' => Pdf::DEST_BROWSER,
                'destination' => Pdf::DEST_FILE,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // enhanced bootstrap css built by Krajee for mPDF formatting
                'cssFile' => 'css/factuur.css',
                 // set mPDF properties on the fly
                'options' => [
                    'title' => $factuur->naam . '.pdf',
                    'subject' => $factuur->naam . '.pdf',
                    //    'keywords' => 'krajee, grid, export, yii2-grid, pdf'
                ],
            ]);
            // return the pdf output as per the destination setting
            if ($pdf->render() != '') {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan pdf niet genereren.'));
                return $this->redirect(['index']);
            }

            if(!$factuur->updateAfterCreateFactuur($user, $new_bij_transacties, $new_af_transacties, $new_turven))
            {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Pdf is gegenereerd, maar record kunnen niet geopdate worden.'));
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

            foreach($factuur->getTransacties()->all() as $transactie) {
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
                    var_dump(4);
                    return FALSE;
                }
            }
            if(!$factuur->delete()){
                $dbTransaction->rollBack();
                return FALSE;
            }
            $dbTransaction->commit();
            unlink($filename);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze factuur niet verwijderen.'));
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
