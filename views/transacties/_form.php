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
use kartik\widgets\FileInput;

?>

<div class="transacties-form">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>

    <?php
    $messageString = 'uitleg_' . Yii::$app->request->get('type_id');
    if($modelTransacties->type_id != null) {
        $messageString = 'uitleg_' . $modelTransacties->type_id ;
    }
    echo Yii::t('betalingstypes', $messageString);

    ?> </br> </br> <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
        'id'   => 'bonnen-form',
        'options'=> ['enctype'=>'multipart/form-data'],
    ]);

    if (in_array(Yii::$app->request->get('type'), ['bankbij_gebruiker', 'izettle_invoer', 'statiegeld', 'declaratie_invoer', 'declaratie_uitbetaling'])) {
        echo $form->field($modelTransacties, 'transacties_user_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
            'options'   => [
                'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
                'id' => 'transacties_user_id',
            ],
        ]);
    }
    if(Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['pin'])) {
        echo $form->field($modelTransacties, 'omschrijving')->textInput();
    }
    if (Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['pin', 'declaratie_invoer', 'izettle_uitbetaling', 'mollie_uitbetaling', 'ing_kosten'])) {
        // Usage with ActiveForm and model
        echo $form->field($modelBonnen, 'image_temp', ['enableClientValidation' => true])->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showCaption' => false,
                'showUpload' => false
            ]
        ]);
        if($modelBonnen->image != null) {
            echo Html::encode('Huidige bon: ' . $modelBonnen->image);
        }
    }
    if (Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['pin', 'declaratie_invoer'])) {
        echo $form->field($modelBonnen, 'soort')->widget(Select2::className(), [
            'data' => $modelBonnen->getSoortOptions(),
            'options' => [
                'placeholder' => Yii::t('app', 'Selecteer soort betaling'),
                'id' => 'soort'
            ],
        ]);
    }

    if (Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['declaratie_uitbetaling', 'izettle_uitbetaling', 'mollie_uitbetaling', 'izettle_kosten', 'mollie_kosten'])) {
        $transactionsArray = $modelTransacties->getTransactionsArray();

        if(Yii::$app->request->get('type') == 'mollie_uitbetaling') {
            $transactionsArray = $modelTransacties->getTransactionsArray([BetalingType::getIdealTerugbetalingId(), BetalingType::getIdealId()],
                [\app\models\Transacties::MOLLIE_STATUS_paid]);
        }
        echo $form->field($modelTransacties, 'all_related_transactions')->widget(Select2::classname(), [
            'name' => 'all_related_transactions',
            'value' => $modelTransacties->all_related_transactions,
            'id' => $modelTransacties->transacties_id,
            'data' => $transactionsArray,
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
    }

    echo $form->field($modelTransacties, 'bedrag')->widget(MaskMoney::classname());
    if (Yii::$app->request->get('type') == 'mollie_uitbetaling') {
        echo $form->field($modelTransacties, 'bedrag_kosten')->widget(MaskMoney::classname());
    }

    if (Yii::$app->request->get('type') == null) {
        echo $form->field($modelTransacties, 'type_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(BetalingType::find()->all(), 'type_id', 'omschrijving'),
            'options'   => [
                'placeholder' => Yii::t('app', 'Selecteer betaling type'),
                'id' => 'type_id',
            ],
        ]);
    }

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
