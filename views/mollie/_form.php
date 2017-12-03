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

?>

<div class="transacties-form">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
        'type' => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo $form->field($model, 'omschrijving')->textInput();
    echo $form->field($model, 'bedrag')->widget(Select2::className(), [
        'data' => [
            10 => '10 euro',
            15 => '15 euro',
            25 => '25 euro',
            50 => '50 euro',
            75 => '75 euro',
            100 => '100 euro'
        ],
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer de hoogte van het teged dat je wilt kopen'),
            'id' => 'turflijst_id',
        ],
    ]);

    echo $form->field($model, 'issuer')->widget(Select2::className(), [
        'data' => $model->getIssuersOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer een bank'),
            'id' => 'issuer'
        ],
    ]);
    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block', 'value'=>$user->id, 'name'=>'submit']);

    ActiveForm::end(); ?>

</div>