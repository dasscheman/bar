<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AssortimentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assortiment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'assortiment_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'merk') ?>

    <?= $form->field($model, 'soort') ?>

    <?= $form->field($model, 'alcohol') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
