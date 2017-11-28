<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use app\models\Assortiment;

/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode(Assortiment::getAssortimentName($assortiment_id) . ' turven voor de volgende gebruikers:') ?>
            </div>
        <div class="panel-body">
            <?php
            echo $this->render('/_alert');

            foreach ($models as $user) {
                if(in_array($user->id, $users)) {
                    echo Html::a(
                        $user->profile->name,
                        [
                            'rondje',
                            'remove' => $user->id,
                            'users' => $users,
                            'assortiment_id' => $assortiment_id],
                        [ 'class' => 'btn-lg btn-info namen' ]
                    );
                }
            }?>
            <?php
            echo Html::a(
                'Opslaan',
                [
                    'rondje',
                    'assortiment_id' => $assortiment_id,
                    'users' => $users,
                    'actie' => 'opslaan',
                ],
                [
                    'class' => 'btn-lg btn-success namen',
                    !empty($users)?'':'disabled' => 'disabled'
                ]
            );

            echo Html::a(
                'Annuleren',
                [
                    'barinvoer',
                    '#' => 'w1-tab1'
                ],
                [ 
                    'class' => 'btn-lg btn-danger namen',
                    'data' => [
                        'confirm' => 'De turven zijn niet opgeslagen',
                    ],
                ]
            );?>
        </div>
    </div>
    <div class="knoppen">
        <?php
        foreach ($models as $user) {
            if(!in_array($user->id, $users)) {
                echo Html::a(
                    $user->profile->name,
                    [
                        'rondje',
                        'user_id' => $user->id,
                        'users' => $users,
                        'assortiment_id' => $assortiment_id],
                    [ 'class' => 'btn-lg btn-success namen' ]
                );
            }
        }?>
    </div>
</div>