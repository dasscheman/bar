<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Bon details') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                echo Html::a(
                    Yii::t('app', 'Bewerken'),
                    [ 'update', 'id' => $model->bon_id ],
                    [ 'class' => 'btn btn-success' ]
                );
                echo Html::a(
                    Yii::t('app', 'Delete'),
                    [ 'delete', 'id' => $model->bon_id ],
                    [ 'class' => 'btn btn-danger', 'data-method'=>'post' ]
                );
                echo Html::a(
                    Yii::t('app', 'Download bon'),
                    ['/bonnen/download', 'id' => $model->bon_id],
                    [
                        'title' => 'Download de bon',
                        'class'=>'btn btn-primary',
                        'target'=>'_blank',
                    ]
                );
                
                ?>

                <table class="table">
                    <?php
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'bon_id',
                            'omschrijving',
                            'image',
                            'type' => [
                                'attribute' => 'type',
                                'value' => function ($model) {
                                    return $model->getTypeText();
                                },
                            ],
                            'datum' => [
                                'attribute' => 'datum',
                                'value' => function ($model) {
                                    return empty($model->datum)?'':Yii::$app->setupdatetime->displayFormat($model->datum, 'php:d-M-Y');
                                },
                            ],
                            'bedrag' => [
                                'attribute' => 'bedrag',
                                'value' => function ($model) {
                                    return number_format($model->bedrag, 2, ',', ' ') . ' €';
                                }
                            ],
                            'created_at',
                            'created_by' => [
                                'attribute' => 'created_by',
                                'value' => function ($model) {
                                    return $model->getCreatedBy()->one()->username;
                                },
                            ],
                            'updated_at',
                            'updated_by' => [
                                'attribute' => 'updated_by',
                                'value' => function ($model) {
                                    return $model->getupdatedBy()->one()->username;
                                },
                            ],
                        ],
                    ]) ?>
                </table>
            </div>
        </div>
    </div>
</div>