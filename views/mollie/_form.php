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
    echo $form->field($model, 'bedrag')->widget(Select2::className(), [
        'data' => [
            '10.00' => '10 euro',
            '15.00' => '15 euro',
            '25.00' => '25 euro',
            '50.00' => '50 euro',
            '75.00' => '75 euro',
            '100.00' => '100 euro'
        ],
        'options'   => [
        'value' => '15.00',
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

    if (!$user->automatische_betaling) {
        echo Html::encode('Je kunt je betalingen automatisch laten uitvoeren op het moment dat je tegoed onder 0 euro komt. '
                . 'Je tegoed wordt dan verhoogd met het bedrag dat je hier invult. '
                . 'Elke mail die je onvangt zal een link bevatten waarmee je eenvoudig automatisch ophogen uit kan zetten.');
        echo $form->field($model, 'automatische_betaling')->checkbox();
    }
    
    echo $form->field($model, 'transacties_user_id')->hiddenInput(['value'=> $model->transacties_user_id])->label(false);
    echo Html::submitButton(Yii::t('app', 'Betalen'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end();
    if ($user->automatische_betaling) {
        ?>
        <br>
        <br>
        <div>
        Je hebt al een automatisch incasso lopen deze kan je hieronder aanpassen.
        </div>
        <br>
        <br>
        <?php
        echo Html::a(
            'Automatisch ophogen aanpassen',
            [ 'mollie/automatisch-betaling-update'],
            [
                'class' => 'btn btn-info btn-block',
                'data' => [
                    'method' => 'post',
                    'params' => ['pay_key' => $user->pay_key],
                ],
            ]
        );
    } ?>

</div>