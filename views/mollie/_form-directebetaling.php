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
    echo $form->field($model, 'omschrijving')->textInput(['readOnly'=> true]);
    echo $form->field($model, 'bedrag')->textInput(['readOnly'=> true]);
    echo $form->field($model, 'transactie_kosten')->textInput(['readOnly'=> true]);
    echo $form->field($model, 'transacties_user_id')->hiddenInput(['value'=> $model->transacties_user_id])->label(false);

    echo $form->field($model, 'issuer')->widget(Select2::className(), [
        'data' => $model->getIssuersOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer een bank'),
            'id' => 'issuer'
        ],
    ]);

    echo Html::submitButton(Yii::t('app', 'Betalen'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end(); ?>

</div>
