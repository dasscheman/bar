<?php
/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Turven;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Turven $searchModel
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

    echo $this->render('/_alert');    echo Html::a(
        Yii::t('app', 'Turven aan turflijst toevoegen'),
        [ '/turven/create'],
        [ 'class' => 'btn btn-success namen']
    );
    ?> <br> <br> <?php
    Pjax::begin();
    echo GridView::widget([
        'id' => 'kv-grid-turven',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'layout'       => "{items}\n{pager}",
        'columns' => [
            'displayname' => [
                'attribute' => 'displayname',
                'value' => function ($model) {
                    return $model->getConsumerUser()->one()->username;
                },
            ],
            [
                'attribute'=>'eenheid_name',
                'format' => 'raw',
                'value'=>function ($model) {
                    return Html::a($model->eenheid->name, ['assortiment/view', 'id' => $model->eenheid->assortiment_id]);
                },
            ],
            'datum' => [
                'attribute' => 'datum',
                'value' => function ($model) {
                    return empty($model->datum)?'':Yii::$app->setupdatetime->displayFormat($model->datum, 'datetime2', true);
                },
            ],
            [
                'attribute'=>'prijslijst_id',
                'format' => 'raw',
                'value'=>function ($model) {
                    return Html::a('Prijslijst ' . $model->prijslijst_id, ['prijslijst/view', 'id' => $model->prijslijst_id]);
                },
             ],
            'aantal',
            'totaal_prijs',
            'type' => [
                'attribute' => 'type',
                'filter'=> function ($model) {
                    return $model->getTypeOptions();
                },
                'value' => function ($model) {
                    return $model->getTypeText();
                },
            ],
            'status' => [
                'attribute' => 'status',
                'filter'=> function ($model) {
                    return $model->getStatusOptions();
                },
                'value' => function ($model) {
                    return $model->getStatusText();
                },
            ],
            [
            'attribute'=>'factuur_id',
                'format' => 'raw',
                'value'=>function ($model) {
                    return empty($model->factuur_id)?'':Html::a('Factuur ' . $model->factuur_id, ['factuur/view', 'id' => $model->factuur_id]);
                },
            ],
            'deleted_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'template' => '{update} {view} {delete}',
                'headerOptions' => ['style' => 'width:10%'],
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        if ($model->deleted_at === null) {
                            return true;
                        }
                        return false;
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
