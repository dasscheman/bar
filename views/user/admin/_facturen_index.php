<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
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
Pjax::begin();
echo GridView::widget([
    'id' => 'kv-grid-factuur',
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions'=>function ($model) {
        if ($model->deleted_at !== null) {
            return ['class' => 'danger'];
        }
    },
    'layout'       => "{items}\n{pager}",
    'columns' => [
        'factuur_id',
        'naam',
        'verzend_datum' => [
            'attribute' => 'verzend_datum',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Actions',
            'template' => '{update} {view} {delete} {download}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        [
                            'facturen-view',
                            'id' => $model->factuur_id,
                        ],
                        [
                            'title' => Yii::t('app', 'View details'),
                        ]
                    );
                },
                'download' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-print"></span>',
                        [
                            '/factuur/download',
                            'id' => $model->factuur_id,
                        ],
                        [
                            'title' => Yii::t('app', 'Get pdf'),
                            'class'=>'btn btn-primary btn-xs',
                            'target'=>'_blank',
                            'data-pjax' => "0"
                        ]
                    );
                },
            ],
            'visibleButtons' => [
                'delete' => function ($model, $key, $index) {
                    if ($model->deleted_at === null) {
                        return true;
                    }
                    return false;
                },
            ],
        ]
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
Pjax::end();
