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
?>

<div class="panel-body">
    <?php
    echo $this->render('/_alert');
    echo Html::a(
        Yii::t('app', 'Favorieten lijst toevoegen'),
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
            'favorieten_lijsten_id',
            'omschrijving',
//                        'favorieten_id',
            'user_id',

            'users_temp' => [
                'attribute' => 'users_temp',
                'value' => function ($model) {
                    return $model->getUsersFavorieten();
                },
            ],
            'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            [
                'class' => 'yii\grid\ActionColumn',
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