<?php

namespace app\controllers;

use Yii;
use app\models\Favorieten;
use app\models\FavorietenLijsten;
use app\models\FavorietenLijstenSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\UserSearch;

/**
 * FavorietenLijstenController implements the CRUD actions for FavorietenLijsten model.
 */
class FavorietenLijstenController extends Controller
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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'favorieten-aanpassen'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'delete', 'create', 'update', 'view'],
                        'roles' =>  ['admin', 'beheerder'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['favorieten-aanpassen'],
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
     * Lists all FavorietenLijsten models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FavorietenLijstenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'main-fluid';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FavorietenLijsten model.
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
     * Creates a new FavorietenLijsten model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FavorietenLijsten();
        $this->layout = 'main-fluid';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $users_temp = Yii::$app->request->post('FavorietenLijsten')['users_temp'];
            foreach ($users_temp as $user) {
                $modelFavorieten = new Favorieten;
                $modelFavorieten->lijst_id = $model->favorieten_lijsten_id;
                $modelFavorieten->selected_user_id = $user;
                $modelFavorieten->save();
            }
            return $this->redirect(['view', 'id' => $model->favorieten_lijsten_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FavorietenLijsten model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelFavorietenOud = Favorieten::findAll(['lijst_id' => $model->favorieten_lijsten_id]);
            if (!empty($modelFavorietenOud)) {
                foreach ($modelFavorietenOud as $modelFavorietOud) {
                    $modelFavorietOud->delete();
                }
            }
            $users_temp = Yii::$app->request->post('FavorietenLijsten')['users_temp'];
            foreach ($users_temp as $user) {
                $modelFavorieten = new Favorieten;
                $modelFavorieten->lijst_id = $model->favorieten_lijsten_id;
                $modelFavorieten->selected_user_id = $user;
                $modelFavorieten->save();
            }
            return $this->redirect(['/turven/barinvoer', '#' => 'w1-tab2']);
        } else {
            $selected_users = Favorieten::find()
                ->where(['lijst_id' => $id])
                ->asArray()
                ->select('selected_user_id')
                ->all();

            $model->users_temp = ArrayHelper::getColumn($selected_users, 'selected_user_id');
           
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FavorietenLijsten model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFavorietenAanpassen($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->get('actie') === 'opslaan') {
            $modelFavorietenOud = Favorieten::findAll(['lijst_id' => $model->favorieten_lijsten_id]);
            if (!empty($modelFavorietenOud)) {
                foreach ($modelFavorietenOud as $modelFavorietOud) {
                    $modelFavorietOud->delete();
                }
            }
            $users_temp = Yii::$app->request->get('users');

            if (!empty($users_temp)) {
                foreach ($users_temp as $user) {
                    $modelFavorieten = new Favorieten;
                    $modelFavorieten->lijst_id = $model->favorieten_lijsten_id;
                    $modelFavorieten->selected_user_id = $user;
                    $modelFavorieten->save();
                }
            }
            return $this->redirect(['/turven/barinvoer', '#' => 'w1-tab2']);
        } else {
            if (Yii::$app->request->get('users') === null) {
                $selected_users = Favorieten::find()
                    ->where(['lijst_id' => $id])
                    ->asArray()
                    ->select('selected_user_id')
                    ->all();

                if (!empty($selected_users)) {
                    $model->users_temp = ArrayHelper::getColumn($selected_users, 'selected_user_id');
                } else {
                    $model->users_temp = [];
                }
            } else {
                $model->users_temp = Yii::$app->request->get('users');
            }

            if (Yii::$app->request->get('remove') !== null) {
                if (($key = array_search(Yii::$app->request->get('remove'), $model->users_temp)) !== false) {
                    unset($model->users_temp[$key]);
                }
            }

            if (Yii::$app->request->get('add') !== null) {
                $model->users_temp[] = Yii::$app->request->get('add');
            }

            $userSearchModel = new UserSearch();
            $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                'users' => $model->users_temp,
                'model' => $model,
                'modelsUsers' => $userDataProvider->getModels(),
            ]);
        }
    }

    /**
     * Deletes an existing FavorietenLijsten model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $modelFavorietenOud = Favorieten::findAll(['lijst_id' => $id]);
        if (!empty($modelFavorietenOud)) {
            foreach ($modelFavorietenOud as $modelFavorietOud) {
                $modelFavorietOud->delete();
            }
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FavorietenLijsten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FavorietenLijsten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FavorietenLijsten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
