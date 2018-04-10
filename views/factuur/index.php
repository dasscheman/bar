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
                <?php
                Pjax::begin();
                echo GridView::widget([
                    'id' => 'kv-grid-factuur',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
                        'factuur_id',
                        'ontvanger' => [
                            'attribute' => 'ontvanger',
                            'value' => function ($model) {
                                return $model->getOntvanger()->one()->username;
                            },
                        ],
                        'naam',
                        'verzend_datum' => [
                            'attribute' => 'verzend_datum',
                        ],
                        'deleted_at',
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
                            'visibleButtons' => [
                                'delete' => function ($model, $key, $index) {
                                    if ($model->deleted_at === null) {
                                        return true;
                                    }
                                    return false;
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
