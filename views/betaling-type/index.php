<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BetalingTypeSearch */
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
$pjax = false; //TRUE zorgt ervoor dat de columnen vertraagd verspringen, dat is irritant.

?>

<div class="panel-body">

    <?php
    echo $this->render('/_alert');
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-betaling-type',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            'omschrijving',
            'bijaf' => [
                'attribute' => 'bijaf',
                'value' => function ($model) {
                    return $model->getBijafText();
                },
                'headerOptions' => ['style' => 'width:5%'],
            ],
            'state' => [
                'attribute' => 'state',
                'value' => function ($model) {
                    return $model->getStateText();
                },
            ],
            'created_at',
            'created_by',
            // 'updated_at',
            // 'updated_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:20%'],],
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
