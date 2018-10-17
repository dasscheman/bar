<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use app\models\Assortiment;
use kartik\helpers\Html;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;

/**
 * @var $this  yii\web\View
 * @var $model app\models\Assortiment
 */

?>

<div class="assortiment-form">
    <?php

    $form = ActiveForm::begin([
        'id' => 'assortiment-form',
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
    ]);

    echo $form->field($model, "assortiment_id", ['showLabels'=>true])->widget(Select2::className(), [
        'data' => ArrayHelper::map(Assortiment::find()->all(), 'assortiment_id', 'name'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer assortiment item'),
            'id' => 'assortiment_id',
        ],
    ]);

    echo $form->field($model, 'type')->widget(Select2::className(), [
        'data' => $model->getTypeOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer type'),
            'id' => 'type'
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

    echo $form->field($model, 'volume')->textInput();
    echo $form->field($model, 'aantal')->textInput();
    echo $form->field($model, 'totaal_volume')->textInput();
    echo $form->field($model, 'totaal_prijs')->textInput();

    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end();?>
</div>
