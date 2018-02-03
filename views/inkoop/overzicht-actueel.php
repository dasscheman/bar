<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Acteeel overzicht van de voorraad') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                foreach ($dataProvider->getModels() as $assortItem) {
                    echo Html::a(
                        $assortItem->omschrijving . ' ' . $assortItem->assortiment->merk . ' (' . $assortItem->totaal_aantal . ')',
                        [
                            'nieuw-geopend',
                            'assortiment_id' => $assortItem->assortiment_id,
                            'omschrijving' => $assortItem->omschrijving,
                        ],
                        [ 'class' => 'btn-lg btn-info namen' ]
                    );
                } ?>
            </div>
        </div>
    </div>
</div>
