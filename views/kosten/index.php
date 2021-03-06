<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\Models\Kosten;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KostenSearch */
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
        'id' => 'kv-grid-kosten',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            [
                'attribute'=>'bon_id',
                'format' => 'raw',
                'value'=>function ($model) {
                    return Html::a($model->bon_id, ['bonnen/view', 'id' => $model->bon_id]);
                },
            ],
            'omschrijving',
            'datum',
            'prijs',
            'type' => [
                'attribute' => 'type',
                'filter'=> function ($model) {
                    return $model->getTypeOptions();
                },
                'value' => function ($model) {
                    return $model->getTypeText();
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'headerOptions' => ['style' => 'width:15%'],
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
