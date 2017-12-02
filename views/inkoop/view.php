<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Inkoop */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Inkoop details') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                echo Html::a(
                    Yii::t('app', 'Bewerken'),
                    [ 'update', 'id' => $model->inkoop_id ],
                    [ 'class' => 'btn btn-success' ]
                );
                echo Html::a(
                    Yii::t('app', 'Delete'),
                    [ 'delete', 'id' => $model->inkoop_id ],
                    [ 'class' => 'btn btn-danger', 'data-method'=>'post' ]
                ); ?>
                <table class="table">
                    <?php
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'inkoop_id',
                            'assortiment_id',
                            'datum',
                            'volume',
                            'aantal',
                            'totaal_prijs',
                            'type',
                            'status',
                            'created_at',
                            'created_by',
                            'updated_at',
                            'updated_by',
                        ],
                    ]) ?>
                </table>
            </div>
        </div>
    </div>
</div>

