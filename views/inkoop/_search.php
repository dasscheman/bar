<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InkoopSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inkoop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'inkoop_id') ?>

    <?= $form->field($model, 'assortiment_id') ?>

    <?= $form->field($model, 'datum') ?>

    <?= $form->field($model, 'inkoper_user_id') ?>

    <?= $form->field($model, 'volume') ?>

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
