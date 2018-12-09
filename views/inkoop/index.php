<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Assortiment;
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
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-inkoop',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            [
                'attribute'=>'assortiment_name',
                'format' => 'raw',
                'value'=>function ($model) {
                    return Html::a($model->assortiment->name, ['assortiment/view', 'id' => $model->assortiment_id]);
                },
            ],
            'assortiment' => [
                'filter' => Assortiment::getAssortimentNameOptions(),
                'attribute' => 'assortiment_id',
                'value' => function ($model) {
                    return $model->assortiment->name;
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
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:15%'],
                'header'=>'Actions',
                'template' => '{update} {view} {delete}',
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
