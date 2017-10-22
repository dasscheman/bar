<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use app\models\Assortiment;
use app\models\User;
use app\models\Transacties;

/* @var $this yii\web\View */
/* @var $model app\models\Inkoop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inkoop-form">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
    ]);

    echo $form->field($model, 'assortiment_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Assortiment::find()->all(), 'assortiment_id', 'name'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer assortiment item'),
            'id' => 'assortiment_id',
        ],
    ]);

    echo $form->field($model, 'transacties_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(
            Transacties::find()->all(),
            'transacties_id',
            function($model, $defaultValue) {
                return $model['transacties_id']. ' (' . $model->getTransactiesUser()->one()->username .') -'.$model['omschrijving'];
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

    echo $form->field($model, 'datum')->widget(DatePicker::className(), [
        'model' => $model,
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

    echo $form->field($model, 'inkoper_user_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'id' => 'inkoper_user_id',
        ],
    ]);
    ?>

    <?= $form->field($model, 'volume')->textInput() ?>

    <?= $form->field($model, 'aantal')->textInput() ?>

    <?= $form->field($model, 'totaal_prijs')->textInput(['maxlength' => true]);

    echo $form->field($model, 'type')->widget(Select2::className(), [
        'data' => $model->getTypeOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer type inkoop'),
            'id' => 'status'
        ],
    ]);

    echo $form->field($model, 'status')->widget(Select2::className(), [
        'data' => $model->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer status inkoop'),
            'id' => 'type'
        ],
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
