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
    foreach ($modelsPrijslijst as $item) {
        echo Html::a(
            $item->getEenheid()->one()->name,
            [ '/turven/rondje', 'prijslijst_id' => $item->prijslijst_id ],
            [ 'class' => 'btn-lg btn-info namen' ]
        );
    } ?>
</div>
