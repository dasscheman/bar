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
        if($user->profile->limit_bereikt) {
            continue;
        }
        echo Html::a(
            $user->profile->name,
            [ 'barinvoer', 'user_id' => $user->id, 'tab' => 'w1-tab2'],
            [ 'class' => 'btn-lg btn-success namen' ]
        );
    }

    echo Html::a(
        'Aanpassen',
        [ '/favorieten-lijsten/favorieten-aanpassen', 'id' => $lijst_id],
        [ 'class' => 'btn-lg btn-warning namen' ]
    );?>
</div>
