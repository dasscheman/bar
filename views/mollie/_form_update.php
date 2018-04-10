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
//use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\helpers\Url;

?>

<div class="transacties-form">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
        'type' => ActiveForm::TYPE_HORIZONTAL,
    ]);
    echo $form->field($model, 'mollie_bedrag')->widget(Select2::className(), [
        'data' => [
            10 => '10 euro',
            15 => '15 euro',
            25 => '25 euro',
            50 => '50 euro',
            75 => '75 euro',
            100 => '100 euro'
        ],
        'value' => $model->mollie_bedrag,
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer de hoogte van het tegoed dat je wilt kopen'),
            'id' => 'mollie_bedrag',
        ],
    ]);

    echo $form->field($model, 'pay_key')->hiddenInput(['value'=> $model->pay_key])->label(false);
?>

    <div class="form-route"><?php

        echo Html::submitButton('Wijzig bedrag', ['class' => 'btn btn-warning namen']);
        echo Html::a(
            'Stop automatisch ophogen',
            [ 'mollie/automatisch-betaling-annuleren'],
            [
                'class' => 'btn btn-success namen',
                'data' => [
                    'method' => 'post',
                    'params' => ['pay_key' => $model->pay_key],
                ],
            ]
        );
 ?>
    </div>
    <?php 
    ActiveForm::end(); ?>

</div>