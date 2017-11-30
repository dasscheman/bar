<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InkoopSearch */
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
                <?= Html::encode('Assortiment overzicht') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert');
                echo $this->render('/_menu');
                Pjax::begin();
                echo GridView::widget([
                    'id' => 'kv-grid-inkoop',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
                        'inkoop_id',
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
                        'volume',
                        'aantal',
                        'totaal_prijs' => [
                            'attribute' => 'totaal_prijs',
                            'value' => function($model){
                                return number_format($model->totaal_prijs, 2, ',', ' ') . ' €';
                            }
                        ],
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
                        // 'created_at',
                        // 'created_by',
                        // 'updated_at',
                        // 'updated_by',

                        ['class' => 'yii\grid\ActionColumn',
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