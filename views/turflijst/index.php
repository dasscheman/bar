<?php
/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use app\components\CustomAlertBlock;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Turflijst $searchModel
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
                <?= Html::encode('Turflijst overzicht') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert') ?>
                <?php echo $this->render('/_menu') ?>
                <table class="table">

                    <?php
                    echo GridView::widget([
                        'id' => 'kv-grid-turflijst',
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'layout'       => "{items}\n{pager}",
                        'columns' => [
                            'turflijst_id',
                            'volgnummer',
                            'start_datum' => [
                                'attribute' => 'start_datum',
                                'value' => function($model){
                                    return Yii::$app->setupdatetime->displayFormat($model->start_datum, 'php:d-M-Y');
                                },
                            ],
                            'end_datum' => [
                                'attribute' => 'end_datum',
                                'value' => function($model){
                                    return Yii::$app->setupdatetime->displayFormat($model->end_datum, 'php:d-M-Y');
                                },
                            ],
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