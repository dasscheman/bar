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
        <div class="panel-body">
            <?php echo $this->render('/_alert'); ?>
            <div class="knoppen">
                <?php
                foreach ($userDataProvider->getModels() as $user) {
                    echo Html::a(
                        $user->profile->name,
                        [ 'barinvoer', 'user_id' => $user->id ],
                        [ 'class' => 'btn-lg btn-success namen' ]
                    );
                }?>
            </div>
        </div>
    </div>
</div>
