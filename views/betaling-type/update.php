<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BetalingType */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Betaling type bijwerken') ?>
            </div>
            <div class="panel-body">
                <?php
                echo $this->render('/_alert');
                echo $this->render('/_menu') ?>
                <table class="table">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </table>
            </div>
        </div>
    </div>
</div>