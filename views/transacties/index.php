<?php
/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Transacties $searchModel
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
                <?= Html::encode('Transacties overzicht') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert') ?>
                <?php echo $this->render('/_menu') ?>
                <table class="table">
                    <?php
                    echo GridView::widget([
                        'id' => 'kv-grid-transacties',
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'layout'       => "{items}\n{pager}",
                        'columns' => [
                            'transacties_id',
                            'transacties_user_id' => [
                                'attribute' => 'transacties_user_id',
                                'value' => function($model){
                                    return $model->getTransactiesUser()->one()->username;
                                },
                            ],
                            'omschrijving',
                            'bedrag',
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
                            [
                                'attribute'=>'factuur_id',
                                'format' => 'raw',
                                'value'=>function ($model) {
                                    return empty($model->factuur_id)?'':Html::a('Factuur ' . $model->factuur_id, ['factuur/view', 'id' => $model->factuur_id]);
                                 },
                             ],
//                            'created_by',
//                            'created_at',
//                            'updated_by',
//                            'updated_at',
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