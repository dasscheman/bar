<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

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
                <?php 
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                
                Pjax::begin();
                echo GridView::widget([
                    'id' => 'kv-grid-bonnen',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
                        'bon_id',
                        'omschrijving',
                        'image',
                        'type',
                        'datum' => [
                            'attribute' => 'datum',
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
                                            'bonnen/download',
                                            'id' => $model->bon_id,
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
                ]);
                Pjax::end();?>
            </div>
        </div>
    </div>
</div>