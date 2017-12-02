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
    foreach ($modelsAssort as $assortItem) {
        echo Html::a(
            $assortItem->name,
            [ '/turven/rondje', 'assortiment_id' => $assortItem->assortiment_id ],
            [ 'class' => 'btn-lg btn-info namen' ]
        );
    } ?>
</div>
