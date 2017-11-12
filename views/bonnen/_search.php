<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BonnenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonnen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bon_id') ?>

    <?= $form->field($model, 'omschrijving') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'datum') ?>

    <?php // echo $form->field($model, 'bedrag') ?>

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
