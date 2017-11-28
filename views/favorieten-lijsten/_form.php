<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;

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

    echo $form->field($model, 'omschrijving')->textInput(['maxlength' => true]);

    echo $form->field($model, 'user_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'id' => 'user_id',
        ],
    ]);
    echo $form->field($model, 'users_temp')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'class' => "form-control",
            'multiple' => TRUE,
            'id' => 'users_temp',
        ],
        'pluginOptions' => [
              'tags' => true,
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
