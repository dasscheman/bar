<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/**
/* @var $this yii\web\View
 * @var $model app\models\Transacties
 */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Traansacties toevoegen') ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert') ?>
                <?php echo $this->render('/_menu') ?>
                <table class="table">
                    <?php
                    if(empty($modelBonnen)) {
                        echo  $this->render('_form', ['modelTransacties' => $modelTransacties]);
                    } else {
                        echo  $this->render('_form-declaratie', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);
                    } ?>
                </table>
            </div>
        </div>
    </div>
</div>