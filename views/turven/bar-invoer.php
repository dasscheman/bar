<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Niewe turven voor ' . User::getUserDisplayName($user_id)) ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                foreach ($assortDataProvider->getModels() as $assortItem) {
                    if (isset($count[$assortItem->assortiment_id])) {
                        $labelName = $assortItem->name . ' <span class="bold-red">' . $count[$assortItem->assortiment_id] . '</span>';
                    } else {
                        $labelName = $assortItem->name;
                    }
                    echo Html::a(
                        $labelName,
                        [
                            'barinvoer',
                            'assortiment_id' => $assortItem->assortiment_id,
                            'count' => $count,
                            'user_id' => $user_id,
                            'actie' => 'toevoegen'
                        ],
                        [ 'class' => 'btn-lg btn-info namen' ]
                    );
                } ?>
            </div>
        </div>
        <?php

        echo Html::a(
            'Opslaan',
            [
                'barinvoer',
                'count' => $count,
                'user_id' => $user_id,
                'actie' => 'opslaan',
            ],
            [
                'class' => 'btn btn-success',
                !empty($count)?'':'disabled' => 'disabled'
            ]
        );
        echo Html::a(
            'Terug',
            [
                'barinvoer',
            ],
            [ 'class' => 'btn btn-danger' ]
        );?>

        <table class="table">

            <?php
            $attributes = [
                [
                    'group'=>true,
                    'label'=>'Nog niet gefactureerde turven:',
                    'rowOptions'=>['class'=>'info'],
        //            'groupOptions'=>['class'=>'text-center']
                ],
            ];
            $turven = $model->newTurvenUsers;
            if (!empty($turven)) {
                $attributes[] = [
                    'columns' => [
                        [
                            'label' => 'Naam',
                            'value' => 'Datum / Turflijstnummer',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:20%'],
                            'labelColOptions'=>['style'=>'width:20%']
                        ],
                        [
                            'label' => 'Prijs per stuk',
                            'value' => 'Totaal',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'labelColOptions'=>['style'=>'width:30%']
                        ]
                    ]
                ];
                foreach ($turven as $turf) {

                    $attributes[] = [
                        'columns' => [
                            [
                                'label' =>$turf->aantal . ' ' . $turf->assortiment->name,
                                'value' =>  empty($turf->datum)? 'Turflijjst: ' . $turf->turflijst->volgnummer: Yii::$app->setupdatetime->displayFormat($turf->datum, 'datetime'),
                                'displayOnly'=>true,
                                'valueColOptions'=>['style'=>'width:20%'],
                                'labelColOptions'=>['style'=>'width:20%']
                            ],
                            [
                                'value' => number_format($turf->totaal_prijs, 2, ',', ' ') . ' €',
                                'label' => number_format($turf->prijslijst->prijs, 2, ',', ' ') . ' per glas',
                                'displayOnly'=>true,
                                'valueColOptions'=>['style'=>'width:30%'],
                                'labelColOptions'=>['style'=>'width:30%']
                            ]
                        ]
                    ];
                }
            } else {
                $attributes[] =
                    [
                        'group'=>true,
                        'label' => 'Geen nieuwe turven',
                        //                'rowOptions'=>['class'=>'info'],
                        //                'groupOptions'=>['class'=>'text-right']
                    ];
            }

            $attributes[] = [
                'group'=>true,
                'label' => 'Totaal turven: ' . number_format($model->sumNewTurvenUsers, 2, ',', ' ') . ' &euro;',
                'rowOptions'=>['class'=>'info'],
                'groupOptions'=>['class'=>'text-right']
            ];
            $attributes[] = [
                'group'=>true,
                'label'=>'Nog niet gefactureerde af transacties',
                'rowOptions'=>['class'=>'info'],
        //            'groupOptions'=>['class'=>'text-center']
            ];

            if(!empty($model->newAfTransactiesUser)) {
                $attributes[] = [
                    'columns' => [
                        [
                            'label' => 'Omschrijving',
                            'value' => 'Tansactie omschrijving',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:20%'],
                            'labelColOptions'=>['style'=>'width:20%']
                        ],
                        [
                            'label' => 'Datum',
                            'value' => 'Bedrag',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'labelColOptions'=>['style'=>'width:30%']
                        ]
                    ]
                ];
                foreach ($model->newAfTransactiesUser as $transactieaf) {
                    $attributes[] = [
                        'columns' => [
                            [
                                'label' => $transactieaf->omschrijving,
                                'value' =>$transactieaf->type->omschrijving,
                                'displayOnly'=>true,
                                'valueColOptions'=>['style'=>'width:20%'],
                                'labelColOptions'=>['style'=>'width:20%']
                            ],
                            [
                                'label' => $transactieaf->datum,
                                'value' => number_format($transactieaf->bedrag, 2, ',', ' ') . ' €',
                                'displayOnly'=>true,
                                'valueColOptions'=>['style'=>'width:30%'],
                                'labelColOptions'=>['style'=>'width:30%']
                            ]
                        ]
                    ];
                }
            } else {
                $attributes[] =
                    [
                        'group'=>true,
                        'label' => 'Geen nieuwe af transacties',
                        //                'rowOptions'=>['class'=>'info'],
                        //                'groupOptions'=>['class'=>'text-right']
                    ];
            }

            $attributes[] = [
                'group'=>true,
                'label' => 'Totaal af transacties: ' . number_format($model->sumNewAfTransactiesUser, 2, ',', ' ') . ' &euro;',
                'rowOptions'=>['class'=>'info'],
                'groupOptions'=>['class'=>'text-right']
            ];
            $attributes[] = [
                'group'=>true,
                'label'=>'Nog niet gefactureerde Bij transacties',
                'rowOptions'=>['class'=>'info'],
        //            'groupOptions'=>['class'=>'text-center']
            ];

            if(!empty($model->newBijTransactiesUser)) {
                $attributes[] = [
                    'columns' => [
                        [
                            'label' => 'Omschrijving',
                            'value' => 'Tansactie omschrijving',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:20%'],
                            'labelColOptions'=>['style'=>'width:20%']
                        ],
                        [
                            'label' => 'Datum',
                            'value' => 'Bedrag',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'labelColOptions'=>['style'=>'width:30%']
                        ]
                    ]
                ];
                foreach ($model->newBijTransactiesUser as $transactiebij) {

                    $attributes[] = [
                        'columns' => [
                            [
                                'label' => $transactiebij->omschrijving,
                                'value' =>$transactiebij->type->omschrijving,
                                'displayOnly'=>true,
                                'valueColOptions'=>['style'=>'width:20%'],
                                'labelColOptions'=>['style'=>'width:20%']
                            ],
                            [
                                'label' => $transactiebij->datum,
                                'value' => number_format($transactiebij->bedrag, 2, ',', ' ') . ' €',
                                'displayOnly'=>true,
                                'valueColOptions'=>['style'=>'width:30%'],
                                'labelColOptions'=>['style'=>'width:30%']
                            ]
                        ]
                    ];
                }
            } else {
                $attributes[] =
                    [
                        'group'=>true,
                        'label' => 'Geen nieuwe bij transacties',
                        //                'rowOptions'=>['class'=>'info'],
                        //                'groupOptions'=>['class'=>'text-right']
                    ];
            }

            $attributes[] = [
                'group'=>true,
                'label' => 'Totaal bij transacties: ' . number_format($model->sumNewBijTransactiesUser, 2, ',', ' ') . ' &euro;',
                'rowOptions'=>['class'=>'info'],
                'groupOptions'=>['class'=>'text-right']
            ];

            $vorig_openstaand =  $model->getSumOldBijTransactiesUser() - $model->getSumOldTurvenUsers() - $model->getSumOldAfTransactiesUser();
            $attributes[] = [
                'group'=>true,
                'label' => 'Totaal openstaand vorige factuur: ' . number_format($vorig_openstaand, 2, ',', ' ') . ' &euro;',
                'rowOptions'=>['class'=>'info'],
                'groupOptions'=>['class'=>'text-right']
            ];


            $nieuw_openstaand = $vorig_openstaand - $model->sumNewTurvenUsers + $model->sumNewBijTransactiesUser - $model->sumNewAfTransactiesUser;
            $attributes[] = [
                'group'=>true,
                'label' => 'Totaal: ' . number_format($nieuw_openstaand, 2, ',', ' ') . ' &euro;',
                'rowOptions'=>['class'=>'info'],
                'groupOptions'=>['class'=>'text-right']
            ];
            
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => TRUE,
                'striped' => TRUE,
                'condensed' => TRUE,
                'responsive' => TRUE,
                'hover' => TRUE,
                'hAlign' => 'middle',
                'vAlign' => 'middle',
                'fadeDelay'=> 700,
                'deleteOptions'=>[ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete'=>true],
                ],
                'container' => ['id'=>'kv-demo'],
                'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            ]);?>
        </table>

    </div>
</div>