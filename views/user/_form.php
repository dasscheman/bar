<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>

<div class="knoppen">
    <?php
    foreach ($models as $user) {
        if (!$user->limitenControleren()) {
            echo Html::a(
                $user->username,
                [ 'barinvoer', 'user_id' => $user->id ],
                [ 'class' => 'btn-lg btn-warning namen' ]
            );
            // Na 1 maart 2018 continue
            continue;
        }
        echo Html::a(
            $user->username,
            [ 'barinvoer', 'user_id' => $user->id ],
            [ 'class' => 'btn-lg btn-success namen' ]
        );
    }?>
</div>
