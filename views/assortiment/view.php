<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\detail\DetailView

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */;

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Assortiment details') ?>
            </div>
            <div class="panel-body">
                <?php 
                echo $this->render('/_alert');
                echo $this->render('/_menu'); ?>
                <div class="view">
                    <?php
                    echo Html::a(
                        Yii::t('app', 'Bewerken'),
                        [ 'update', 'id' => $model->assortiment_id ],
                        [ 'class' => 'btn btn-success' ]
                    );
                    echo Html::a(
                        Yii::t('app', 'Delete'),
                        [ 'delete', 'id' => $model->assortiment_id ],
                        [ 'class' => 'btn btn-danger', 'data-method'=>'post' ]
                    ); ?>
                    <table class="table">
                        <?php
                        echo DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'assortiment_id',
                                'name',
                                'merk',
                                'soort',
                                'alcohol',
                                'change_stock_auto',
                                'volume',
                                'status',
                                'created_at',
                                'created_by',
                                'updated_at',
                                'updated_by',
                            ],
                        ])
                        ?>
                    </table>
                S</div>
            </div>
        </div>
    </div>
</div>
