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
                foreach ($prijslijstDataProvider->getModels() as $item) {
                    if (isset($count[$item->eenheid_id])) {
                        $labelName = $item->getEenheid()->one()->name . ' <span class="bold-red">' . $count[$item->eenheid_id] . '</span>';
                    } else {
                        $labelName = $item->getEenheid()->one()->name;
                    }
                    echo Html::a(
                        $labelName,
                        [
                            'barinvoer',
                            'prijslijst_id' => $item->eenheid_id,
                            'count' => $count,
                            'user_id' => $user_id,
                            'actie' => 'toevoegen',
                            'tab' => $tab
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
                'tab' => $tab
            ],
            [
                'class' => 'btn-lg btn-success',
                !empty($count)?'':'disabled' => 'disabled'
            ]
        );
        echo Html::a(
            'Annuleren',
            [
                'barinvoer',
                '#' => $tab
            ],
            [
                'class' => 'btn-lg btn-danger',
                'data' => [
                    'confirm' => 'Je turven zijn niet opgeslagen',
                ],
            ]
        );
        echo $this->render('/user/overzicht', ['model' => $model]); ?>
