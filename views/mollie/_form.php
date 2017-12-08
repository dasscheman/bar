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
use yii\helpers\ArrayHelper;
use app\models\User;

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
            'placeholder' => Yii::t('app', 'Selecteer de hoogte van het tegoed dat je wilt kopen'),
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

    echo Html::encode('Je kunt je betalingen automatisch laten uitvoeren op het moment dat je tegoed onder 0 euro komt. '
            . 'Je tegoed wordt dan verhoogd met het bedrag dat je hier invult. '
            . 'Elke mail die je onvangt zal een link bevatten waarmee je eenvoudig automatisch verhogen uit kan zetten.');
    echo $form->field($model, 'automatische_betaling')->checkbox();
    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block', ['value'=>$user->id, 'name'=>'user']]);

    ActiveForm::end(); ?>

</div>