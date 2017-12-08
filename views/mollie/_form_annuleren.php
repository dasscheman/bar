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
    echo $form->field($model, 'mollie_bedrag')->widget(Select2::className(), [
        'data' => [
            10 => '10 euro',
            15 => '15 euro',
            25 => '25 euro',
            50 => '50 euro',
            75 => '75 euro',
            100 => '100 euro'
        ],
//        'value' => $model->mollie_bedrag,
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer de hoogte van het tegoed dat je wilt kopen'),
            'id' => 'mollie_bedrag',
        ],
    ]);
    echo Html::a(
            'Wijzig bedrag',
            ['/mollie/automatisch-betaling-updaten', 'value'=>'lasdjfh', 'name'=>'user'],
            [
                'class' => 'btn btn-lg btn-success namen',
                'title' => 'Bedrag voor automatisch ophogen wijzigen',
                'data-confirm' => 'Het ',
//                'data-method' => 'POST',
            ]
    );


    echo Html::submitButton(
            Yii::t('app', 'Wijzig bedrag'),
            ['/mollie/automatisch-betaling-annuleren', 'value'=>$model->id, 'name'=>'user'],
            ['class' => 'btn btn-success namen']
    );
    echo Html::a('Wijzig bedrag', ['/mollie/automatisch-betaling-updaten', 'value'=>'lasdjfh', 'name'=>'user'], [
        'title' => 'Bedrag voor automatisch ophogen wijzigen',
        'data-confirm' => 'Het ',
        'data-method' => 'POST',
    ]);
    echo Html::a('Wijzig bedrag', ['/mollie/automatisch-betaling-updaten', ['value'=>'lasdjfh', 'name'=>'user']], [
        'title' => 'Bedrag voor automatisch ophogen wijzigen',
        'data-confirm' => 'Het ',
        'data-method' => 'POST',
    ]);
    echo Html::submitButton(Yii::t('app', 'Wijzig bedrag'), ['class' => 'btn btn-success namen'], '/mollie/automatisch-betaling-annuleren', ['value'=>$model->id, 'name'=>'user']);
    echo Html::submitButton(Yii::t('app', 'Stop automatisch ophogen'), ['class' => 'btn btn-danger namen'], ['/mollie/update', ['value'=>$model->id, 'name'=>'user']]);
    echo Html::submitButton(Yii::t('app', 'Ongewijzigd laten'), ['class' => 'btn btn-warning namen', ['value'=>$model->id, 'name'=>'user']]);

    ActiveForm::end(); ?>

</div>