<?php

namespace app\controllers;

use Yii;
use app\models\Eenheid;
use app\models\Prijslijst;
use app\models\PrijslijstSearch;
use app\models\Turven;
use app\models\TurvenSearch;
use app\models\Transacties;
use app\models\User;
use app\models\UserSearch;
use dektrium\user\filters\AccessRule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'barinvoer', 'rondje'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'delete', 'create', 'update', 'view'],
                        'roles' =>  ['admin', 'beheerder'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['barinvoer', 'rondje'],
                        'roles' =>  ['gebruiker'],
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
     * Lists all Turven models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TurvenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('beheer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Turven models.
     * @return mixed
     */
    public function actionBarinvoer()
    {
        $count = [];

        $prijslijstSearchModel = new PrijslijstSearch();
        $prijslijstDataProvider = $prijslijstSearchModel->searchAvailable(Yii::$app->request->queryParams);
        if (Yii::$app->request->get('user_id') !== null) {
            $user_id = Yii::$app->request->get('user_id');
            if (Yii::$app->request->get('count') !== null) {
                $count = Yii::$app->request->get('count');
            }

            if (Yii::$app->request->get('prijslijst_id') !== null) {
                $prijslijst_id = Yii::$app->request->get('prijslijst_id');
                if (isset($count[$prijslijst_id])) {
                    $count[$prijslijst_id]++;
                } else {
                    $count[$prijslijst_id] = 1;
                }
            }

            if (Yii::$app->request->get('actie') === 'opslaan' &&
                $count !== null &&
                Turven::saveBarInvoer($user_id, $count)) {
                $message = 'Volgende turven zijn toegevoegd bij ' . User::getUserDisplayName($user_id) . ': ';
                $i = 0;
                foreach ($count as $prijslijst_id => $aantal) {

                    if ($i === 0) {
                        $message .= $aantal . ' ' . Prijslijst::findOne($prijslijst_id)->getEenheid()->name;
                    } else {
                        $message .= ', ' . $aantal . ' ' . Prijslijst::findOne($prijslijst_id)->getEenheid()->name;
                    }
                    $i++;
                }
                Yii::$app->session->setFlash('warning', $message);
                $count = [];
                $tab = '';
                if (!empty(Yii::$app->request->get('tab'))) {
                    $tab = 'w2-tab2';
                }
                return $this->redirect(['barinvoer', '#' => $tab]);
            }

            if (!User::limitenControleren($user_id)) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Wat errug, betalen pannenkoek! Vanaf 1 maart kun je niet meer turven als je meer dan 20 euro in het rood staat.'));
            }
            return $this->render('bar-invoer', [
                'prijslijstSearchModel' => $prijslijstSearchModel,
                'prijslijstDataProvider' => $prijslijstDataProvider,
                'count' => $count,
                'user_id' => $user_id,
                'model' => User::findOne($user_id),
                'tab' => Yii::$app->request->get('tab'),
            ]);
        }
        $userSearchModel = new UserSearch();
        $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('/user/gebruiker-selecteren', [
            'userSearchModel' => $userSearchModel,
            'userDataProvider' => $userDataProvider,
            'prijslijstSearchModel' => $prijslijstSearchModel,
            'prijslijstDataProvider' => $prijslijstDataProvider,
        ]);
    }

    public function actionRondje()
    {
        $userSearchModel = new UserSearch();
        $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams);
        $prijslijst_id = Yii::$app->request->get('prijslijst_id');

        if (Yii::$app->request->get('users') === null) {
            $users = [];
        } else {
            $users = Yii::$app->request->get('users');
        }

        if (Yii::$app->request->get('actie') === 'opslaan' &&
            Turven::saveRondje($users, $prijslijst_id)) {
            $message = 'Eén ' . Prijslijst::getDisplayName($prijslijst_id) . ' voor: ';
            $i = 0;
            foreach ($users as $user_id) {
                if ($i === 0) {
                    $message .= User::getUserDisplayName($user_id);
                } else {
                    $message .= ', ' . User::getUserDisplayName($user_id);
                }
                $i++;
            }
            Yii::$app->session->setFlash('warning', $message);

            return $this->redirect([
                '/turven/barinvoer',
                '#' => 'w2-tab1'
            ]);
        }


        if (Yii::$app->request->get('user_id') !== null) {
            $users[] = Yii::$app->request->get('user_id');
        }

        if (Yii::$app->request->get('remove') !== null) {
            if (($key = array_search(Yii::$app->request->get('remove'), $users)) !== false) {
                unset($users[$key]);
            }
        }

        return $this->render('rondje', [
            'models' => $userDataProvider->getModels(),
            'prijslijst_id' => $prijslijst_id,
            'users' => $users
        ]);
    }
    /**
     * Displays a single Turven model.
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
     * Creates a new Turven model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->cache->flush();
        $models = [new Turven()];
        for ($i = 1; $i < 5; $i++) {
            $models[] = new Turven();
        }

        $this->layout = 'main-fluid';
        if (Turven::loadMultiple($models, Yii::$app->request->post())) {
            $count = 0;
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($models as $model) {
                    if (empty($model->eenheid_id)) {
                        continue;
                    }

                    if ($count > 0) {
                        $model->datum = $models[0]->datum;
                        $model->turflijst_id = $models[0]->turflijst_id;
                        $model->consumer_user_id = $models[0]->consumer_user_id;
                        $model->status = $models[0]->status;
                    }

                    if (empty($model->datum)) {
                        $prijslijst = Prijslijst::determinePrijslijstTurflijstIdBased($model->eenheid_id, $model->turflijst_id);
                    } elseif (empty($model->turflijst_id)) {
                        $prijslijst = Prijslijst::determinePrijslijstDateBased($model->eenheid_id, $model->datum);
                    }

                    if (!$prijslijst) {
                        if (empty($model->turflijst_id)) {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is geen geldige turflijst voor ' . $model->getEenheid()->one()->name));
                        } else {
                            Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is geen geldige prijs voor ' . $model->geteenheid()->one()->name));
                        }
                        return $this->render('create', [
                            'models' => $models,
                        ]);
                    }

                    $model->totaal_prijs = number_format($model->aantal * $prijslijst->prijs, 2);
                    $model->prijslijst_id = $prijslijst->prijslijst_id;
                    $model->type = Turven::TYPE_turflijst;
                    $model->status = Turven::STATUS_gecontroleerd;

                    if (!$model->save()) {
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
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze turven niet toevoegen.') . $e);
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
        Yii::$app->cache->flush();
        $model = $this->findModel($id);

        $this->layout = 'main-fluid';
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
        Yii::$app->cache->flush();
        $model = $this->findModel($id);
        $factuur = $model->getFactuur()->one();

        if (empty($factuur)) {
            $model->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            if (!$model->save()) {
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }

                return $this->redirect(['index']);
            }
            return $this->redirect(['index']);
        }

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
                if ($model->turven_id == $turf->turven_id) {
                    // Deze gaan we sowieso verwijderen, ik weet niet of het
                    // goed gaat als dit record dan eerst gewijzigd wordt.
                    continue;
                }
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
            $model->deleted_at = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            if (!$model->save()) {
                foreach ($model->errors as $key => $error) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan: ' . $key . ':' . $error[0]));
                }

                $dbTransaction->rollBack();
                return $this->redirect(['index']);
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt deze turven niet verwijderen: ') . $e);
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
