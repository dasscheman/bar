<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

?>

<div class="panel-body">
    <?php echo $this->render('/_alert'); ?>
    <div class="col-sm-6 col-md-6 col-lg-6" >
        <?php
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
                    'deleted_at',
                ],
            ])
        ?>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6" >
        <?php
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute'=>'bon_id',
                        'format' => 'html',
                        'visible' => $model->isBonRequired(),
                        'value'=>function ($model) {
                            if($model->bon_id != null) {
                                return Html::a($model->bon_id, ['bonnen/view', 'id' => $model->bon_id]);
                            }
                            $ids = '';
                            $count = 0;
                            if($model->relatedBonnen() != null) {
                                foreach ($model->relatedBonnen() as $relatedBon) {
                                    $count++;
                                    $ids .= Html::a($relatedBon, ['bonnen/view', 'id' => $relatedBon]);
                                    if ($count < count($model->relatedBonnen())) {
                                        $ids .= ', ';
                                    }
                                }
                                return $ids;
                            }
                            if($model->bon_id == null && $model->isBonRequired()) {
                                return '<div class="warning"> Bon mist en is verplicht </div>';
                            }
                            return 'nvt';
                        },
                    ],
                    [
                        'attribute' => 'inkoop',
                        'format' => 'html',
                        'visible' => ($model->isInkoopRequired() || (isset($model->bon) && $model->bon->getInkoops()->exists())),
                        'value' => function ($model) {
                            if (!$model->getBon()->exists()) {
                                return 'nvt';
                            }
                            $ids = '';
                            $count = 0;
                            if($model->getBon()->exists()) {
                                foreach ($model->bon->getInkoops()->all() as $related_inkoop) {
                                    $count++;
                                    $ids .= Html::a($related_inkoop->inkoop_id, ['inkopen/view', 'id' => $related_inkoop->inkoop_id]);
                                    if ($count < $model->bon->getInkoops()->count()) {
                                        $ids .= ', ';
                                    }
                                }
                            }

                            if ($ids !== '') {
                                return $ids;
                            }
                            if (!$model->getBon()->exists() && $model->isInkoopRequired()) {
                                return '<div class="warning">Voeg eerst een bon toe </div>';
                            }
                            if (!$model->bon->getInkoops()->exists() && $model->isInkoopRequired()) {
                                return '<div class="warning"> De bon heeft geen link met inkopen of met kosten en minimaal 1 van de 2 is verplicht </div>';
                            }
                            return 'nvt';
                        },
                    ],
                    'kosten_id' => [
                        'attribute' => 'Kosten',
                        'format'=>'html',
                        'visible' => ($model->isKostenRequired() || (isset($model->bon) && $model->bon->getKostens()->exists())),
                        'value' => function ($model) {
                            if (!$model->getBon()->exists()) {
                                return 'nvt';
                            }
                            $ids = '';
                            $count = 0;
                            if($model->getBon()->exists() ) {
                                foreach ($model->bon->getKostens()->all() as $related_kost) {
                                    $count++;
                                    $ids .= Html::a($related_kost->kosten_id, ['kosten/view', 'id' => $related_kost->kosten_id]);
                                    if ($count < $model->bon->getKostens()->count()) {
                                        $ids .= ', ';
                                    }
                                }
                            }
                            if($ids !== '') {
                                return $ids;
                            }

                            if (!$model->getBon()->exists() && $model->isKostenRequired()) {
                                return '<div class="warning">Voeg eerst een bon toe </div>';
                            }
                            if (!$model->bon->getKostens()->exists() && $model->isKostenRequired()) {
                                return '<div class="warning"> De bon heeft geen link met inkopen of met kosten en minimaal 1 van de 2 is verplicht </div>';
                            }
                            return 'nvt';
                        },
                    ],
                    'all_related_transactions' => [
                        'attribute' => 'all_related_transactions',
                        'format'=>'raw',
                        'visible' => $model->isTransactionRequired(),
                        'value' => function ($model) {
                            $ids = '';
                            $count = 0;
                            $model->setAllRelatedTransactions();
                            if($model->all_related_transactions != null) {
                                foreach ($model->all_related_transactions as $related_transaction) {
                                    $count++;
                                    $ids .= Html::a($related_transaction, ['transacties/view', 'id' => $related_transaction]);
                                    if ($count < count($model->all_related_transactions)) {
                                        $ids .= ', ';
                                    }
                                }
                                return $ids;
                            }

                            if($model->all_related_transactions == null && $model->isTransactionRequired()) {
                                return '<div class="warning"> Er is geen link met een transactie en dat is verplicht </div>';
                            }
                            return 'nvt';
                        },
                    ]
                ],
            ]);

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
    </div>
</div>