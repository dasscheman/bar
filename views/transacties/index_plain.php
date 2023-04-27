<?php
/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Transacties $searchModel
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
$toolbar = true;

?>

<div class="panel-body">
    <?php
    echo $this->render('/_alert');
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-transacties',
        'dataProvider' => $dataProvider,
        'rowOptions'=>function ($model) {
            return ['class' => $model->getRowClassDirecteBetaling()];
        },
        'layout'       => "{items}\n{pager}",
        'columns' => [
            [
                'header' => Yii::t('app', 'Turven'),
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column) {
                    return $this->render('/turven/_table_view', ['model' => $model]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true,
                'expandTitle' => Yii::t('app', 'Open detail view'),
                'collapseTitle' => Yii::t('app', 'Close detail view'),
            ],
            'datum' => [
                'attribute' => 'datum',
                'value' => function ($model) {
                    return Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                },
            ],
            'bedrag' => [
                'attribute' => 'bedrag',
                'label' => 'Bedrag',
                'value' => function ($model) {
                    return number_format($model->bedrag, 2, ',', ' ') . ' €';
                }
            ],
            'transactie_kosten' => [
                'attribute' => 'transactie_kosten',
                'label' => 'iDEAL kosten',
                'value' => function ($model) {
                    return number_format($model->transactie_kosten, 2, ',', ' ') . ' €';
                }
            ],
            'status' => [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusText();
                },
            ],
            'mollie_status' => [
                'attribute' => 'mollie_status',
                'value' => function ($model) {
                    return $model->getMollieStatusText();
                },
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
