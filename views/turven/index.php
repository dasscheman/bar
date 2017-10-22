<?php
/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Turven $searchModel
 */

$bordered = FALSE;
$striped = TRUE;
$condensed = TRUE;
$responsive = FALSE;
$hover = TRUE;
$pageSummary = FALSE;
$heading = FALSE;
$exportConfig = FALSE;
$responsiveWrap = FALSE;
$toolbar = FALSE;

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Turven overzicht') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert') ?>
                <?php echo $this->render('/_menu') ?>
                <table class="table">
                    <?php echo GridView::widget([
                        'id' => 'kv-grid-turven',
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'layout'       => "{items}\n{pager}",
                        'columns' => [
                            'turven_id',
                            [
                                'attribute'=>'turflijst_id',
                                'format' => 'raw',
                                'value'=>function ($model) {
                                     return Html::a('Turfijst ' . $model->turflijst_id, ['turflijst/view', 'id' => $model->turflijst_id]);
                                 },
                            ],
                            [
                                'attribute'=>'assortiment_id',
                                'format' => 'raw',
                                'value'=>function ($model) {
                                     return Html::a($model->getAssortiment()->one()->name, ['assortiment/view', 'id' => $model->assortiment_id]);
                                 },
                             ],
                            'datum' => [
                                'attribute' => 'datum',
                                'value' => function($model){
                                    return empty($model->datum)?'':Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                                },
                            ],
                            [
                                'attribute'=>'prijslijst_id',
                                'format' => 'raw',
                                'value'=>function ($model) {
                                     return Html::a('Prijslijst ' . $model->prijslijst_id, ['prijslijst/view', 'id' => $model->prijslijst_id]);
                                 },
                             ],
                            'consumer_user_id' => [
                                'attribute' => 'consumer_user_id',
                                'value' => function($model){
                                    return $model->getConsumerUser()->one()->username;
                                },
                            ],
                            'aantal',
                            'totaal_prijs',
                            'type' => [
                                'attribute' => 'type',
                                'value' => function($model){
                                    return $model->getTypeText();
                                },
                            ],
                            'status' => [
                                'attribute' => 'status',
                                'value' => function($model){
                                    return $model->getStatusText();
                                },
                            ],
                            'factuur_id' => [
                                'attribute' => 'factuur_id',
                                'value' => function($model){
                                    return empty($model->factuur_id)?'':'Factuur ' . $model->factuur_id;
                                },
                            ],
//                            'created_at',
//                            'created_by',
//                            'created_at',
//                            'created_by',
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
                    ]); ?>
                </table>
            </div>
        </div>
    </div>
</div>