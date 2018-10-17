<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Assortiment;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Assortiment $searchModel
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
$pjax = false; //TRUE zorgt ervoor dat de columnen vertraagd verspringen, dat is irritant.
?>

<div class="panel-body">
    <?php
    echo $this->render('/_alert');
    echo Html::a(
        Yii::t('app', 'Assortiment toevoegen'),
        [ 'create'],
        [ 'class' => 'btn btn-success namen']
    );
    ?> <br> <br> <?php
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-assortiment',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            'afschrijving_id',
            'assortiment_id',
            'datum:datetime',
            'volume',
            'aantal',
            'totaal_volume',
            'totaal_prijs',
            'type',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'headerOptions' => ['style' => 'width:10%'],
                'template' => '{update} {view} {delete}',
            ],
        ],

        'toolbar'=> $toolbar,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'responsiveWrap' => $responsiveWrap,
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax' => $pjax, // pjax is set to always true for this demo
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
