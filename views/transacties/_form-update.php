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
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use app\models\BetalingType;
use app\models\Bonnen;
use app\models\Transacties;
use app\models\User;

?>

<div class="transacties-form">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>

    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
        'id'   => 'bonnen-form',
        'options'=> ['enctype'=>'multipart/form-data'],
    ]);

    echo $form->field($modelTransacties, 'transacties_user_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'id' => 'transacties_user_id',
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);

    echo $form->field($modelTransacties, 'omschrijving')->textInput();

    echo $form->field($modelBonnen, 'bon_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(
            Bonnen::find()->orderBy(['bon_id' => SORT_DESC])->all(),
            'bon_id',
            function ($modelBonnen, $defaultValue) {
                return $modelBonnen['omschrijving']. ' (' . $modelBonnen->bon_id .')';
            }
        ),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer transactie'),
            'id' => 'transacties_id',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    echo $form->field($modelBonnen, 'image_temp')->fileInput();
    echo Html::encode('Huidige bon: ' . $modelBonnen->image);

    echo $form->field($modelBonnen, 'soort')->widget(Select2::className(), [
        'data' => $modelBonnen->getSoortOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer soort betaling'),
            'id' => 'soort'
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

    echo $form->field($modelTransacties, 'bedrag')->widget(MaskMoney::classname());

        echo $form->field($modelTransacties, 'type_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(BetalingType::find()->all(), 'type_id', 'omschrijving'),
            'options'   => [
                'placeholder' => Yii::t('app', 'Selecteer betaling type'),
                'id' => 'type_id',
            ],
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
