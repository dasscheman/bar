<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\money\MaskMoney;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Eenheid;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Prijslijst */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="prijslijst-form">
    <?php

    $form = ActiveForm::begin([
        'id' => 'prijslijst-form',
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
    ]);

    echo $form->field($model, 'eenheid_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Eenheid::find()->all(), 'eenheid_id', 'name'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer eenheid item'),
            'id' => 'eenheid_id',
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
