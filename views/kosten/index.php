<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KostenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
                <?= Html::encode('Voorraad history') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert');
                echo $this->render('/_menu');
                Pjax::begin(); ?>    <?= GridView::widget([
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
        </div>
    </div>
</div>