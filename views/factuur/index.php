<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Factuur $searchModel
 */

$bordered = FALSE;
$striped = TRUE;
$condensed = TRUE;
$responsive = FALSE;
$hover = TRUE;
$pageSummary = FALSE;
$heading = FALSE;
$exportConfig = FALSE;
$responsiveWrap = FALSE;
$toolbar = FALSE;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Facturen overzicht') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert') ?>
                <?php echo $this->render('/_menu') ?>
                <table class="table">

                <?php
                    echo GridView::widget([
                        'id' => 'kv-grid-factuur',
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'layout'       => "{items}\n{pager}",
                        'columns' => [
                            'factuur_id',
                            'ontvanger' => [
                                'attribute' => 'ontvanger',
                                'value' => function($model){
                                    return $model->getOntvanger()->one()->username;
                                },
                            ],
                            'naam',
                            'verzend_datum' => [
                                'attribute' => 'verzend_datum',
                                'value' => function($model){
                                    return empty($model->datum)?'':Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                                },
                            ],
                            'created_at',
                            'created_by',
                            'created_at',
                            'created_by',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header'=>'Actions',
                                'template' => '{update} {view} {delete} {download}',
                                'buttons' => [
                                    'download' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="glyphicon glyphicon-print"></span>',
                                            [
                                                'factuur/download',
                                                'id' => $model->factuur_id,
                                            ],
                                            [
                                                'title' => Yii::t('app', 'Get pdf'),
                                                'class'=>'btn btn-primary btn-xs',
                                                'target'=>'_blank',
                                                'data-pjax' => "0"
                                            ]
                                        );
                                    },
                                ],
                            ]
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
                    ]); ?>

                </table>
            </div>
        </div>
    </div>
</div>
