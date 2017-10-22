<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
/**
 * @var $model dektrium\rbac\models\Role
 * @var $this  yii\web\View
 */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Assortiment bijwerken') ?>
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