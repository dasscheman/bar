<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Transacties;
use app\models\BetalingType;
use yii\helpers\ArrayHelper;

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
                    'id' => 'kv-grid-transacties',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'rowOptions'=>function ($model) {
                        if ($model->deleted_at !== null) {
                            return ['class' => 'danger'];
                        }
                    },
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
                        'transacties_id' => [
                            'attribute' => 'transacties_id',
                            'headerOptions' => ['style' => 'width:4%']
                        ],
                        'datum' => [
                            'attribute' => 'datum',
                            'value' => function ($model) {
                                return Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                            },
                        ],
                        'omschrijving',
                        'bedrag' => [
                            'attribute' => 'bedrag',
                            'headerOptions' => ['style' => 'width:6 %']
                        ],
                        'type_id' => [
                            'attribute' => 'type_id',
                            'filter'=> ArrayHelper::map(BetalingType::find()->asArray()->all(), 'type_id', 'omschrijving'),
                            'value' => function ($model) {
                                return $model->getType()->one()->omschrijving;
                            },
                        ],
                        'status' => [
                            'attribute' => 'status',
                            'filter'=> $searchModel->getStatusOptions(),
                            'value' => function ($model) {
                                return $model->getStatusText();
                            },
                        ],
//                        'mollie_status' => [
//                            'attribute' => 'mollie_status',
//                            'value' => function ($model) {
//                                return $model->getMollieStatusText();
//                            },
//                        ],
//                        [
//                            'attribute'=>'all_related_transactions',
//                            'headerOptions' => ['style' => 'width:2%'],
//                            'format' => 'raw',
//                            'value'=>function ($model) {
//                                $ids = '';
//                                $model->setAllRelatedTransactions();
//                                if ($model->all_related_transactions === null) {
//                                    return;
//                                }
//                                $count = 0;
//                                foreach ($model->all_related_transactions as $related_transaction) {
//                                    $count++;
//                                    $ids .= Html::a($related_transaction, ['transacties/view', 'id' => $related_transaction]);
//                                    if ($count < count($model->all_related_transactions)) {
//                                        $ids .= ', ';
//                                    }
//                                }
//                                return $ids;
//                            },
//                        ],
//                        [
//                            'attribute'=>'bon_id',
//                            'format' => 'raw',
//                            'value'=>function ($model) {
//                                return empty($model->bon_id)?'':Html::a('Bon ' . $model->bon_id, ['bonnen/view', 'id' => $model->bon_id]);
//                            },
//                        ],
//                        [
//                            'attribute'=>'factuur_id',
//                            'format' => 'raw',
//                            'value'=>function ($model) {
//                                return empty($model->factuur_id)?'':Html::a('Factuur ' . $model->factuur_id, ['factuur/view', 'id' => $model->factuur_id]);
//                            },
//                         ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header'=>'Actions',
                            'template' => '{update} {view} {delete}',
                            'headerOptions' => ['style' => 'width:8%'],
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
                Pjax::end();
