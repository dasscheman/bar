<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\Factuur
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;

?>

<div class="bonnen-form">
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'enableAjaxValidation'   => false,
        'id'   => 'bonnen-form',
        'options'=> ['enctype'=>'multipart/form-data'],
    ]);

    echo $form->field($model, 'omschrijving')->textarea();
    echo $form->field($model, 'type')->widget(Select2::className(), [
        'data' => $model->getTypeOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer type bon'),
            'id' => 'type'
        ],
    ]);

    echo $form->field($model, 'soort')->widget(Select2::className(), [
        'data' => $model->getSoortOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer soort bon'),
            'id' => 'soort'
        ],
    ]);
    echo $form->field($model, 'image_temp', ['enableClientValidation' => true])->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showCaption' => false,
            'showUpload' => false
        ]
    ]);
    echo Html::encode('Huidige bon: ' . $model->image);

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

    echo $form->field($model, 'bedrag')->widget(MaskMoney::classname());
    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end() ?>
</div>