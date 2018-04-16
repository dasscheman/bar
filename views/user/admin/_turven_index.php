<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Turven;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Turven $searchModel
 */

$bordered = false;
$striped = true;
$condensed = true;
$responsive = false;
$hover = true;
$pageSummary = false;
$heading = false;
$exportConfig = false;
$responsiveWrap = false;
$toolbar = false;

   Pjax::begin();
                echo GridView::widget([
                    'id' => 'kv-grid-turven',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'rowOptions'=>function ($model) {
                        if ($model->deleted_at !== null) {
                            return ['class' => 'danger'];
                        }
                    },
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
//                        'displayname' => [
//                            'attribute' => 'displayname',
//                            'value' => function ($model) {
//                                return $model->getConsumerUser()->one()->username;
//                            },
//                        ],
                        [
                            'attribute'=>'assortiment_name',
                            'format' => 'raw',
                            'value'=>function ($model) {
                                return Html::a($model->assortiment->name, ['assortiment/view', 'id' => $model->assortiment_id]);
                            },
                        ],
                        'datum' => [
                            'attribute' => 'datum',
                            'value' => function ($model) {
                                return empty($model->datum)?'':Yii::$app->setupdatetime->displayFormat($model->datum, 'datetime2', true);
                            },
                        ],
                        [
                            'attribute'=>'prijslijst_id',
                            'format' => 'raw',
                            'value'=>function ($model) {
                                return Html::a('Prijslijst ' . $model->prijslijst_id, ['prijslijst/view', 'id' => $model->prijslijst_id]);
                            },
                         ],
                        'aantal',
                        'totaal_prijs' => [
                            'attribute' => 'totaal_prijs',
                            'value' => function ($model) {
                                return round($model->totaal_prijs, 2);
                            },
                        ],
                        'status' => [
                            'attribute' => 'status',
                            'filter'=> Turven::getStatusOptions(),
                            'value' => function ($model) {
                                return $model->getStatusText();
                            },
                        ],
                        [
                        'attribute'=>'factuur_id',
                            'format' => 'raw',
                            'value'=>function ($model) {
                                return empty($model->factuur_id)?'':Html::a('Factuur ' . $model->factuur_id, ['factuur/view', 'id' => $model->factuur_id]);
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header'=>'Actions',
                            'template' => '{update} {view} {delete}',
                            'headerOptions' => ['style' => 'width:10%'],
                            'visibleButtons' => [
                                'delete' => function ($model, $key, $index) {
                                    if ($model->deleted_at === null) {
                                        return true;
                                    }
                                    return false;
                                },
                            ],
                        ],
                    ],

                    'toolbar'=> $toolbar,
                    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
                    'responsiveWrap' => $responsiveWrap,
                    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                    'pjax'=>true, // pjax is set to always true for this demo
                    'bordered'=>$bordered,
                    'striped'=>$striped,
                    'condensed'=>$condensed,
                    'responsive'=>$responsive,
                    'hover'=>$hover,
                    'showPageSummary'=>$pageSummary,
                    'persistResize'=>false,
                ]);
                Pjax::end();?>

