<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use app\models\Assortiment;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $model app\models\Eenheid
 */

?>

<div class="eenheid-form">
    <?php

    $form = ActiveForm::begin([
        'id' => 'eenheid-form',
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

    echo $form->field($model, 'name')->textInput();
    echo $form->field($model, 'volume')->textInput();

    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end();?>
</div>
