<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use app\models\Inkoop;

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
                        $assortItem->totaal_aantal . ' X ' . $assortItem->omschrijving . ' (<i>' . $assortItem->assortiment->merk . '</i>)',
                        [
                            'voorraad-bij-werken',
                            'assortiment_id' => $assortItem->assortiment_id,
                            'omschrijving' => $assortItem->omschrijving,
                            'status' => Inkoop::STATUS_verkocht,
                        ],
                        [ 'class' => 'btn-lg btn-info namen' ]
                    );
                } ?>
            </div>
            <?php
            if (Yii::$app->user->can('beheerder')) {
                ?>
                <br>
                <br>
                <div class="panel-heading">
                    <?= Html::encode('Afschrijven van voorraad') ?>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($dataProvider->getModels() as $assortItem) {
                        echo Html::a(
                            $assortItem->totaal_aantal . ' X ' . $assortItem->omschrijving . ' (<i>' . $assortItem->assortiment->merk . '</i>)',
                            [
                                'voorraad-bij-werken',
                                'assortiment_id' => $assortItem->assortiment_id,
                                'omschrijving' => $assortItem->omschrijving,
                                'status' => Inkoop::STATUS_afgeschreven,
                            ],
                            [ 'class' => 'btn-lg btn-warning namen' ]
                        );
                    } ?>
                </div>
            <?php
            } ?>
        </div>
    </div>
</div>
