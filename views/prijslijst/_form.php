<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\money\MaskMoney;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Assortiment;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Prijslijst */
/* @var $form yii\widgets\ActiveForm */

?>

<?php
//     $form->field($model, 'assortiment_id')->textInput();
//
//    $form->field($model, 'prijs')->textInput(['maxlength' => true]) ;?>

<div class="assortiment-form">
    <?php

    $form = ActiveForm::begin([
        'id' => 'prijslijst-form',
        'enableClientValidation' => true,
        'enableAjaxValidation'   => true,
    ]);

    echo $form->field($model, 'assortiment_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Assortiment::find()->all(), 'assortiment_id', 'name'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer assortiment item'),
            'id' => 'assortiment_id',
        ],
    ]);

    echo $form->field($model, 'prijs')->widget(MaskMoney::classname());

    echo $form->field($model, 'from')->widget(DatePicker::className(), [

        'model' => $model,
        'attribute' => 'from',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //    'value' => '23-Feb-1982',
        'options'   => [
            'placeholder' => Yii::t('app', 'Start datum'),
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);

    echo $form->field($model, 'to')->widget(DatePicker::className(), [

        'model' => $model,
        'attribute' => 'to',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    //    'value' => '23-Feb-1982',
        'options'   => [
            'placeholder' => Yii::t('app', 'Eind datum'),
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);

    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end();?>
</div>
