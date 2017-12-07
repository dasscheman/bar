<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BetalingTypeSearch */
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
                <?= Html::encode('Betaling type overzicht') ?>
            </div>
            <div class="panel-body">

                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'omschrijving',
                        'bijaf' => [
                            'attribute' => 'bijaf',
                            'value' => function($model){
                                return $model->getBijafText();
                            },
                            'headerOptions' => ['style' => 'width:5%'],
                        ],
                        'state' => [
                            'attribute' => 'state',
                            'value' => function($model){
                                return $model->getStateText();
                            },
                        ],
                        'created_at',
                        'created_by',
                        // 'updated_at',
                        // 'updated_by',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['style' => 'width:20%'],],
                    ],
                ]);
                Pjax::end();?>
            </div>
        </div>
    </div>
</div>
