<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

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

    echo $form->field($model, 'name')->textInput();
    echo $form->field($model, 'merk')->textInput();
    echo $form->field($model, 'soort')->widget(Select2::className(), [
        'data' => $model->getSoortOptions(),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer soort'),
            'id' => 'soort',
    //        'multiple' => true
        ],
    ]);

    echo $form->field($model, 'alcohol')->checkbox();
    echo $form->field($model, 'change_stock_auto')->checkbox();
    echo $form->field($model, 'volume')->textInput();
    echo $form->field($model, 'status')->widget(Select2::className(), [
        'data' => $model->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer status'),
            'id' => 'status'
        ],
    ]);
    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end();?>
</div>