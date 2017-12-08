<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Assortiment;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Assortiment $searchModel
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
$pjax = FALSE; //TRUE zorgt ervoor dat de columnen vertraagd verspringen, dat is irritant.
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Assortiment overzicht') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                Pjax::begin();
                echo GridView::widget([
                    'id' => 'kv-grid-assortiment',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'layout'       => "{items}\n{pager}",
                    'columns' => [
                        'name',
                        'merk',
                        'soort' => [
                            'attribute' => 'soort',
                            'value' => function($model){
                                return $model->getSoortText();
                            },
                            'filter' => Assortiment::getSoortOptions(),
                        ],
                        'status' => [
                            'attribute' => 'status',
                            'value' => function($model){
                                return $model->getStatusText();
                            },
                            'filter' => Assortiment::getStatusOptions(),
                        ],
                        'alcohol' => [
                            'attribute' => 'alcohol',
                            'headerOptions' => ['style' => 'width:2%'],
                            // translate lookup value
                            'value' => function ($model) {
                                $boolean = [
                                    '0' => 'Nee',
                                    '1' => 'Ja'
                                ];
                                return $boolean[$model->alcohol];
                            },
                            'filter' => [
                                '0' => 'Nee',
                                '1' => 'Ja'
                            ],
                        ],
                        'volume',
                        'prijs' => [
                            'attribute' => 'prijs',
                            'value' => function($model){
                                if(isset($model->getPrijs()->one()->prijs)) {
                                    return number_format($model->getPrijs()->one()->prijs, 2, ',', ' ') . ' €';
                                }
                                return 'geen prijs';
                            }
                        ],
                        'totaal' => [
                            'attribute' => 'totaal',
                            'value' => function($model){
                                return $model->getTotaalTurven();
                            }
                        ],
                        'opbrengst' => [
                            'attribute' => 'opbrengst',
                            'value' => function($model){
                                if(null !== $model->getOpbrengstTurven()) {
                                    return number_format($model->getOpbrengstTurven(), 2, ',', ' ') . ' €';
                                }
                                return 'geen prijs';
                            }
                        ],
                        'inkoop' => [
                            'attribute' => 'inkoop',
                            'value' => function($model){
                                if(null !== $model->getTotaalInkoop()) {
                                    return number_format($model->getTotaalInkoop(), 2, ',', ' ') . ' €';
                                }
                                return 'geen prijs';
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['style' => 'width:20%'],
                            'template' => '{update} {view} {delete}',
                        ],
                    ],

                    'toolbar'=> $toolbar,
                    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
                    'responsiveWrap' => $responsiveWrap,
                    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                    'pjax' => $pjax, // pjax is set to always true for this demo
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
