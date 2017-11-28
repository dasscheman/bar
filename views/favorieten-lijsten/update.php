<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use app\models\Assortiment;

/* @var $this yii\web\View */
/* @var $model app\models\FavorietenLijsten */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo Html::encode($model->omschrijving) ?>
            </div>
        <div class="panel-body">
            <?php
            echo $this->render('/_alert');

            if(!empty($modelsUsers)) {
                foreach ($modelsUsers as $user) {
                    if(in_array($user->id, $users)) {
                        echo Html::a(
                            $user->profile->name,
                            [
                                'favorieten-aanpassen',
                                'id' => $model->favorieten_lijsten_id,
                                'remove' => $user->id,
                                'users' => $users],
                            [ 'class' => 'btn-lg btn-info namen' ]
                        );
                    }
                }
            }?>
            <?php
            echo Html::a(
                'Opslaan',
                [
                    'favorieten-aanpassen',
                    'id' => $model->favorieten_lijsten_id,
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
                    '/turven/barinvoer',
                    '#' => 'w1-tab2'
                ],
                [ 
                    'class' => 'btn-lg btn-danger namen',
                    'data' => [
                        'confirm' => 'Je wijzegingen zijn niet opgeslagen',
                    ],
                ]
            );?>
        </div>
    </div>
    <div class="knoppen">
        <?php
        foreach ($modelsUsers as $user) {
            if(!in_array($user->id, $users)) {
                echo Html::a(
                    $user->profile->name,
                    [
                        'favorieten-aanpassen',
                        'id' => $model->favorieten_lijsten_id,
                        'add' => $user->id,
                        'users' => $users],
                    [ 'class' => 'btn-lg btn-success namen' ]
                );
            }
        }?>
    </div>
</div>
