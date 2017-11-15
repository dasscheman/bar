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
use kartik\widgets\DatePicker;
use kartik\select2\Select2;

?>

<div class="bonnen-form">
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'enableAjaxValidation'   => false,
        'id'   => 'bonnen-form',
        'options'=> ['enctype'=>'multipart/form-data'],
//        'action' => ['bonnen/create'],// important
    ]);

    echo $form->field($model, 'omschrijving')->textarea();
    echo $form->field($model, 'type')->widget(Select2::className(), [
        'data' => $model->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer type bon'),
            'id' => 'type'
        ],
    ]);
    
    echo $form->field($model, 'image_temp')->fileInput();
    
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

    echo $form->field($model, 'bedrag')->textInput(['maxlength' => true]);
    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end() ?>
</div>