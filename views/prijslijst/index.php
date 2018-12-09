<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Assortiment;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrijslijstSearch */
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
    <?php echo $this->render('/_alert');
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-prijslijst',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            [
                'attribute'=>'assortiment_id',
                'format' => 'raw',
                'filter'=> ArrayHelper::map(Assortiment::find()->asArray()->all(), 'assortiment_id', 'name'),
                'value'=>function ($model) {
                    return Html::a($model->getEenheid()->one()->name, ['assortiment/view', 'id' => $model->getEenheid()->one()->assortiment_id]);
                },
             ],
            'prijs',
            'from' => [
                'attribute' => 'from',
                'value' => function ($model) {
                    return Yii::$app->setupdatetime->displayFormat($model->from, 'php:d-M-Y');
                },
            ],
            'to' => [
                'attribute' => 'to',
                'value' => function ($model) {
                    return Yii::$app->setupdatetime->displayFormat($model->to, 'php:d-M-Y');
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'template' => '{update} {view} {delete}',
                'headerOptions' => ['style' => 'width:14%'],
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
