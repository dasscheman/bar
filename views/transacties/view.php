<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

$this->beginContent('../views/_beheer2.php');
?>

<div class="panel-body">
    <?php
    echo $this->render('/_alert'); ?>
    <div class="view">
        <?php
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
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'transacties_id',
                'omschrijving',
                'bedrag' => [
                    'attribute' => 'bedrag',
                    'value' => function ($model) {
                        return number_format($model->bedrag, 2, ',', ' ') . ' €';
                    }
                ],
                'type_id' => [
                    'attribute' => 'type_id',
                    'value' => function ($model) {
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
                    'value' => function ($model) {
                        return $model->getStatusText();
                    },
                ],
                'transacties_user_id' => [
                    'attribute' => 'transacties_user_id',
                    'value' => function ($model) {
                        if ($model->getTransactiesUser()->one() !== null) {
                            return $model->getTransactiesUser()->one()->username;
                        }
                    },
                ],
                'all_related_transactions' => [
                    'attribute' => 'all_related_transactions',
                    'format'=>'raw',
                    'value' => function ($model) {
                        $ids = '';
                        $count = 0;
                        foreach ($model->all_related_transactions as $related_transaction) {
                            $count++;
                            $ids .= Html::a($related_transaction, ['transacties/view', 'id' => $related_transaction]);
                            if ($count < count($model->all_related_transactions)) {
                                $ids .= ', ';
                            }
                        }
                        return $ids;
                    },
                ],

                'created_at',
                'created_by' => [
                    'attribute' => 'created_by',
                    'value' => function ($model) {
                        if(isset($model->getCreatedBy()->one()->username)){
                            return $model->getCreatedBy()->one()->username;
                        }
                    },
                ],
                'updated_at',
                'updated_by' => [
                    'attribute' => 'updated_by',
                    'value' => function ($model) {
                        return empty($model->getupdatedBy()->one())? '':$model->getupdatedBy()->one()->username;
                    },
                ],
            ],
        ]) ?>
    </div>
</div>
<?php
$this->endContent();
