<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TurvenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="turven-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'turven_id') ?>

    <?= $form->field($model, 'turflijst_id') ?>

    <?= $form->field($model, 'eenheid_id') ?>

    <?= $form->field($model, 'prijzen_id') ?>

    <?= $form->field($model, 'consumer_user_id') ?>

    <?php // echo $form->field($model, 'aantal') ?>

    <?php // echo $form->field($model, 'totaal_prijs') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
