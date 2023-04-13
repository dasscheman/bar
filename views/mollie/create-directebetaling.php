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
                Directe betaling
            </div>
            <div class="panel-body">
                <?php echo $this->render('/_alert') ?>
                <table class="table">


                    <?php
                    echo  $this->render('_form-directebetaling', [
                        'model' => $model]);?>
                </table>
            </div>
        </div>
    </div>
</div>
