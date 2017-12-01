<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\Transacties
 */

use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
//use kartik\widgets\TouchSpin
use kartik\touchspin\TouchSpin;
?>

<div class="transacties-form">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
//        'type' => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo $form->field($modelTransacties, 'omschrijving')->textInput();

    echo $form->field($modelTransacties, 'bedrag')->widget(Select2::className(), [
        'data' => [10 => 'tien euro', 15 => 'tien5 euro', 25 => 'tien25 euro', 50 => 'tien50 euro', 75 => 'tien75 euro'],
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer turflijst'),
            'id' => 'turflijst_id',
        ],
    ]);

    echo $form->field($modelTransacties, 'issuer')->widget(Select2::className(), [
        'data' => $mollie->getIssuersOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer een bank'),
            'id' => 'issuer'
        ],
    ]);

    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end(); ?>

</div>