<?php

namespace app\controllers\user;

use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\filters\AccessControl;
use app\models\User;
use app\components\AccessRule;

class AdminController extends BaseAdminController
{
    public function behaviors()
	{
//		return [
//			'access' => [
//			    'class' => AccessControl::className(),
//			    'ruleConfig' => [
//			        'class' => AccessRule::className(),
//			    ],
//			    'rules' => [
//			        [
//			            'actions' => ['index', 'update', 'update-profile', 'create', 'info'],
//			            'allow' => true,
//			            'roles' => [User::ROL_beheerder],
//			        ],
//			        [
//			            // 'actions' => ['view', 'search'],
//			            // 'allow' => true,
//			            // 'roles' => ['?', '@', 10],
//			        ],
//			    ],
//			],
//		];
	}
}
