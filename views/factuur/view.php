<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Factuur */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Facturen overzicht') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu');
                echo Html::a(
                    Yii::t('app', 'Bewerken'),
                    [ 'update', 'id' => $model->factuur_id ],
                    [ 'class' => 'btn btn-success' ]
                );
                echo Html::a(
                    Yii::t('app', 'Delete'),
                    [ 'delete', 'id' => $model->factuur_id ],
                    [ 'class' => 'btn btn-danger', 'data-method'=>'post' ]
                );
                echo Html::a(
                    Yii::t('app', 'Download factuur'),
                    ['factuur/download', 'id' => $model->factuur_id],
                    [
                        'title' => Yii::t('app', 'Get pdf'),
                        'class'=>'btn btn-primary',
                        'target'=>'_blank',
                    ]
                );



                ?>
                <table class="table">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'factuur_id',
                            'naam',
                            'verzend_datum',
                            'pdf',
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
