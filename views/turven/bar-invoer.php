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
       