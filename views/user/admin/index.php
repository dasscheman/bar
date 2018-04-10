<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \dektrium\user\models\UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;


echo $this->render('/_alert', ['module' => Yii::$app->getModule('user')]);

echo $this->render('/admin/_menu');

Pjax::begin();

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'layout'       => "{items}\n{pager}",
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:90px;'], # 90px is sufficient for 5-digit user ids
        ],
        'username',
        'email:email',
        [
          'attribute' => 'last_login_at',
          'value' => function ($model) {
              if (!$model->last_login_at || $model->last_login_at == 0) {
                  return Yii::t('user', 'Never');
              } elseif (extension_loaded('intl')) {
                  return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->last_login_at]);
              } else {
                  return date('Y-m-d G:i:s', $model->last_login_at);
              }
          },
        ],
        [
            'header' => 'limit_hard',
            'value' => function ($model) {
                return $model->getProfile()->one()->limit_hard;
            },
        ],
        'sumNewBijTransactiesUser',
//        'sumNewAfTransactiesUser',
//        'sumOldBijTransactiesUser',
//        'sumOldAfTransactiesUser',
        'sumNewTurvenUsers',
//        'sumOldTurvenUsers',
        [
            'header' => 'balans',
            'value' => function ($model) {
                $vorig_openstaand =  $model->getSumOldBijTransactiesUser() - $model->getSumOldTurvenUsers() - $model->getSumOldAfTransactiesUser();
                $nieuw_openstaand = $vorig_openstaand - $model->sumNewTurvenUsers + $model->sumNewBijTransactiesUser - $model->sumNewAfTransactiesUser;
                return $nieuw_openstaand;
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{switch} {resend_password} {update} {delete}',
            'headerOptions' => ['style' => 'width:20%'],
            'buttons' => [
                'resend_password' => function ($url, $model, $key) {
                    if (!$model->isAdmin) {
                        return '
                    <a data-method="POST" data-confirm="' . Yii::t('user', 'Are you sure?') . '" href="' . Url::to(['resend-password', 'id' => $model->id]) . '">
                    <span title="' . Yii::t('user', 'Generate and send new password to user') . '" class="glyphicon glyphicon-envelope">
                    </span> </a>';
                    }
                },
                'switch' => function ($url, $model) {
                    if ($model->id != Yii::$app->user->id && Yii::$app->getModule('user')->enableImpersonateUser) {
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', ['/user/admin/switch', 'id' => $model->id], [
                            'title' => Yii::t('user', 'Become this user'),
                            'data-confirm' => Yii::t('user', 'Are you sure you want to switch to this user for the rest of this Session?'),
                            'data-method' => 'POST',
                        ]);
                    }
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end() ?>
