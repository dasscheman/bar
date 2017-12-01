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
                <?= Html::encode('Transactie details') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                echo Html::a(
                    Yii::t('app', 'Bewerken'),
                    [ 'update', 'id' => $model->transacties_id ],
                    [ 'class' => 'btn btn-success' ]
                );
                echo Html::a(
                    Yii::t('app', 'Delete'),
                    [ 'delete', 'id' => $model->transacties_id ],
                    [ 'class' => 'btn btn-danger', 'data-method'=>'post' ]
                );

                if (!empty($model->bon_id)) {
                    echo Html::a(
                        Yii::t('app', 'Download bon'),
                        ['/bonnen/download', 'id' => $model->bon_id],
                        [
                            'title' => 'Download de bon',
                            'class'=>'btn btn-primary',
                            'target'=>'_blank',
                        ]
                    );
                }
                ?>
                <table class="table">
                    <?php
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'transacties_id',
                            'transacties_user_id' => [
                                'attribute' => 'transacties_user_id',
                                'value' => function($model){
                                    return $model->getTransactiesUser()->one()->username;
                                },
                            ],
                            'omschrijving',
                            'bedrag' => [
                                'attribute' => 'bedrag',
                                'value' => function($model){
                                    return number_format($model->bedrag, 2, ',', ' ') . ' €';
                                }
                            ],
                            'type_id' => [
                                'attribute' => 'type_id',
                                'value' => function($model){
                                    return $model->type->omschrijving;
                                },
                            ],
                            [
                                'attribute'=>'bon_id',
                                'format' => 'raw',
                                'value'=>function ($model) {
                                     return Html::a($model->bon_id, ['bonnen/view', 'id' => $model->bon_id]);
                                },
                            ],
                            'status' => [
                                'attribute' => 'status',
                                'value' => function($model){
                                    return $model->getStatusText();
                                },
                            ],
                            'created_at',
                            'created_by' => [
                                'attribute' => 'created_by',
                                'value' => function($model){
                                    return $model->getCreatedBy()->one()->username;
                                },
                            ],
                            'updated_at',
                            'updated_by' => [
                                'attribute' => 'updated_by',
                                'value' => function($model){
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