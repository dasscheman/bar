<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Inkoop;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InkoopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

<div class="panel-body">
    <?php
    echo $this->render('/_alert');
    echo Html::a(
        Yii::t('app', 'Voorraad toevoegen'),
        [ 'create'],
        [ 'class' => 'btn btn-success namen']
    );
    ?> <br> <br> <?php
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-inkoop',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            'omschrijving',
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
                    return empty($model->datum)?'':Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                },
            ],
            'volume',
            'aantal',
            'totaal_prijs' => [
                'attribute' => 'totaal_prijs',
                'value' => function ($model) {
                    return number_format($model->totaal_prijs, 2, ',', ' ') . ' €';
                }
            ],
            'type' => [
                'attribute' => 'type',
                'filter'=> Inkoop::getTypeOptions(),
                'value' => function ($model) {
                    return $model->getTypeText();
                },
            ],
            'status' => [
                'attribute' => 'status',
                'filter'=> Inkoop::getStatusOptions(),
                'value' => function ($model) {
                    return $model->getStatusText();
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:15%'],
                'header'=>'Actions',
                'template' => '{update} {view} {delete} {verbruikt} {afschrijven}',
                'buttons' => [
                    'verbruikt' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-shopping-cart"></span>',
                            [
                                'inkoop/verbruikt',
                                'id' => $model->inkoop_id,
                            ],
                            [
                                'title' => 'Noteer als verbruikt',
                                'class'=>'btn btn-primary btn-xs',
                            ]
                        );
                    },
                    'afschrijven' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-exclamation-sign"></span>',
                            [
                                'inkoop/afschrijven',
                                'id' => $model->inkoop_id,
                            ],
                            [
                                'title' => 'Schrijf Voorraad af',
                                'class'=>'btn btn-primary btn-xs',
                            ]
                        );
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
</div>
     