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
            echo Html::button(
                $user->profile->name,
                ['class' => 'btn btn-lg btn-danger namen disabled']
            );
            // Na 1 maart 2018 alleen continue
            continue;
        }
        echo Html::a(
            $user->profile->name,
            [ 'barinvoer', 'user_id' => $user->id, 'tabIndex' => 4],
            [ 'class' => 'btn-lg btn-success namen' ]
        );
    }

    echo Html::a(
        'Aanpassen',
        [ '/favorieten-lijsten/favorieten-aanpassen', 'id' => $lijst_id],
        [ 'class' => 'btn-lg btn-warning namen' ]
    );?>
</div>
