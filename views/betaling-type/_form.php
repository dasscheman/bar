<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BetalingType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="betaling-type-form">

    <?php $form = ActiveForm::begin([
        'id' => 'assortiment-form',
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
    ]);

    echo $form->field($model, 'omschrijving')->textInput(['maxlength' => true]);
    echo $form->field($model, 'bijaf')->widget(Select2::className(), [
        'data' => $model->getBijAfOptions(),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer bij/af'),
            'id' => 'bijaf',
    //        'multiple' => true
        ],
    ]);

    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end();?>
</div>
