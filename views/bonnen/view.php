<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

?>

<div class="panel-body">
    <?php echo $this->render('/_alert'); ?>
    <div class="col-sm-6 col-md-6 col-lg-6" >

        <?php
        echo $this->render('/_alert');

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
            ]);
        ?>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6" >
        <?php
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
                    'data-pjax' => 0,
                ]
            );

            $image = Url::to('@web/uploads/bonnen/' . $model->image);
            if(@is_array(getimagesize($image))) {
                echo Html::img($image, ['height' => "100%", 'width' => "100%"]);
            } else {
                echo '<br>';
                echo 'Alleen jpg, png en gifs hebben een preview.';
            }
        ?>
    </div>
</div>