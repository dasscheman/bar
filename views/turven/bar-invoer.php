<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\models\User;


/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Nieuwe turven voor ' . $model->username) ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                if ($model->limitenControleren()) {
                    foreach ($prijslijstDataProvider->getModels() as $item) {
                        if (isset($count[$item->prijslijst_id])) {
                            $labelName = $item->getEenheid()->one()->name . ' <span class="bold-red">' . $count[$item->prijslijst_id] . '</span>';
                        } else {
                            $labelName = $item->getEenheid()->one()->name;
                        }
                        echo Html::a(
                            $labelName,
                            [
                                'barinvoer',
                                'prijslijst_id' => $item->prijslijst_id,
                                'count' => $count,
                                'user_id' => $model->id,
                                'actie' => 'toevoegen',
                                'tabIndex' => $tabIndex
                            ],
                            ['class' => 'btn-lg btn-info namen']
                        );
                    }
                } ?>
            </div>
        </div>
        <?php

        if (!$model->limitenControleren()) {
            echo Html::button(
                'Opslaan',
                [ 'class' => 'btn btn-lg btn-succes namen disabled']
            );
        } else {
            echo Html::a(
            'Opslaan',
                [
                    'barinvoer',
                    'count' => $count,
                    'user_id' => $model->id,
                    'actie' => 'opslaan',
                    'tabIndex' => $tabIndex
                ],
                [
                    'class' => 'btn-lg btn-success',
                    !empty($count)?'':'disabled' => 'disabled'
                ]
            );

        }

        echo Html::a(
            'Annuleren',
            [
                'barinvoer',
                'tabIndex' => $tabIndex
            ],
            [
                'class' => 'btn-lg btn-danger',
                'data' => [
                    'confirm' => 'Je turven zijn niet opgeslagen',
                ],
            ]
        );
        echo $this->render('/user/overzicht', ['model' => $model]); ?>
    </div>
</div>
