<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\Transacties
 */

use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\User;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use app\models\BetalingType;
use app\models\Transacties;

?>

<div class="transacties-form">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
    ]);
    echo $form->field($modelTransacties, 'transacties_user_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'id' => 'transacties_user_id',
        ],
    ]);
    echo $form->field($modelTransacties, 'omschrijving')->textInput();
    echo $form->field($modelTransacties, 'bedrag')->widget(MaskMoney::classname());
    echo $form->field($modelTransacties, 'type_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(BetalingType::find()->all(), 'type_id', 'omschrijving'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer betaling type'),
            'id' => 'type_id',
        ],
    ]);
    echo $form->field($modelTransacties, 'status')->widget(Select2::className(), [
        'data' => $modelTransacties->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer status betaling'),
            'id' => 'status'
        ],
    ]);
    
    echo $form->field($modelTransacties, 'all_related_transactions')->widget(Select2::classname(), [
        'name' => 'all_related_transactions',
        'value' => $modelTransacties->all_related_transactions,
        'id' => $modelTransacties->transacties_id,
        'data' => $modelTransacties->getTransactionsArray(),
        'options' => [
            'placeholder' => 'Filter as you type ...',
            'id' => $modelTransacties->transacties_id,
            'class' => "form-control",
            'multiple' => true,
        ],
        'pluginOptions' => [
              'tags' => true,
        ]
    ]);

    echo $form->field($modelTransacties, 'datum')->widget(DatePicker::className(), [
        'model' => $modelTransacties,
        'attribute' => 'datum',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //    'value' => '23-Feb-1982',
        'options'   => [
            'placeholder' => Yii::t('app', 'Datum'),
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end(); ?>
</div>