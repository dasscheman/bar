<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $model dektrium\rbac\models\Role
 * @var $this  yii\web\View
 */

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Turven bijwerken') ?>
            </div>
            <div class="panel-body">

                <?php echo $this->render('/_alert') ?>
                <?php echo $this->render('/_menu') ?>
                <table class="table">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </table>
            </div>
        </div>
    </div>
</div>