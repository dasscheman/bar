<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\Turflijst
 */

use kartik\widgets\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="turflijst-form">
    <?php

    $form = ActiveForm::begin([
        'id' => 'turflijst-form',
        'enableClientValidation' => true,
        'enableAjaxValidation'   => true,
    ]);

    echo $form->field($model, 'volgnummer');

    echo $form->field($model, 'start_datum')->widget(DatePicker::className(), [

        'model' => $model,
        'attribute' => 'start_datum',
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

    echo $form->field($model, 'end_datum')->widget(DatePicker::className(), [

        'model' => $model,
        'attribute' => 'end_datum',
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

    ActiveForm::end(); ?>

</div>