<?php
/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Transacties;
use app\models\BetalingType;
use yii\helpers\ArrayHelper;
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
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                Pjax::begin();
                echo GridView::widget([
                    'id' => 'kv-grid-transacties',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
                        'displayname' => [
                            'attribute' => 'displayname',
                            'value' => function($model){
                                return $model->getTransactiesUser()->one()->username;
                            },
                        ],
                        'datum' => [
                            'attribute' => 'datum',
                            'value' => function($model){
                                return Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                            },
                        ],
                        'omschrijving',
                        'bedrag',
                        'type_id' => [
                            'attribute' => 'type_id',
                            'filter'=> ArrayHelper::map(BetalingType::find()->asArray()->all(), 'type_id', 'omschrijving'),
                            'value' => function($model){
                                return $model->getType()->one()->omschrijving;
                            },
                        ],
                        'status' => [
                            'attribute' => 'status',
                            'filter'=> Transacties::getStatusOptions(),
                            'value' => function($model){
                                return $model->getStatusText();
                            },
                        ],
                        [
                            'attribute'=>'bonnen_id',
                            'format' => 'raw',
                            'value'=>function ($model) {
                                 return Html::a($model->bon_id, ['bonnen/view', 'id' => $model->bon_id]);
                            },
                        ],
                        [
                            'attribute'=>'factuur_id',
                            'format' => 'raw',
                            'value'=>function ($model) {
                                return empty($model->factuur_id)?'':Html::a('Factuur ' . $model->factuur_id, ['factuur/view', 'id' => $model->factuur_id]);
                             },
                         ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header'=>'Actions',
                            'template' => '{update} {view} {delete}',
                            'headerOptions' => ['style' => 'width:16%'],
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