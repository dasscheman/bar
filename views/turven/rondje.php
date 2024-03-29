<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use app\models\Prijslijst;

/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($prijslijst->getDisplayName() . ' turven voor de volgende gebruikers:') ?>
            </div>
        <div class="panel-body">
            <?php
            echo $this->render('/_alert');

            foreach ($models as $user) {
                if (in_array($user->id, $users)) {
                    echo Html::a(
                        $user->username,
                        [
                            'rondje',
                            'remove' => $user->id,
                            'users' => $users,
                            'prijslijst_id' => $prijslijst->prijslijst_id,
                            'tabIndex' => $tabIndex
                        ],
                        [ 'class' => 'btn-lg btn-info namen' ]
                    );
                }
            }?>
            <?php
            echo Html::a(
                'Opslaan',
                [
                    'rondje',
                    'prijslijst_id' => $prijslijst->prijslijst_id,
                    'users' => $users,
                    'actie' => 'opslaan',
                    'tabIndex' => $tabIndex
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
                    'tabIndex' => $tabIndex
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
            if (!in_array($user->id, $users)) {
                if (!$user->limitenControleren()) {
                    echo Html::button(
                        $user->profile->name,
                        [ 'class' => 'btn btn-lg btn-danger namen disabled' ]
                    );
                    // Na 1 maart 2018 continue
                    continue;
                }
                echo Html::a(
                    $user->username,
                    [
                        'rondje',
                        'user_id' => $user->id,
                        'users' => $users,
                        'prijslijst_id' => $prijslijst->prijslijst_id,
                        'tabIndex' => $tabIndex
                    ],
                    [ 'class' => 'btn-lg btn-success namen' ]
                );
            }
        }?>
    </div>
</div>
