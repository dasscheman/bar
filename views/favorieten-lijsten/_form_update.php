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

    echo $form->field($model, 'users_temp')->widget(Select2::className(), [
        'value' => $model->users_temp,
        'id' => 'user_temp',
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
        <?php
        echo Html::submitButton('Update', ['class' => 'btn-lg btn-primary namen']);
//        echo Html::a(
//            'Opslaan',
//            [ 'update', 'id' => $model->favorieten_lijsten_id],
//            [ 'class' => 'btn-lg btn-primary namen' ]
//        );
        echo Html::a(
            'Annuleren',
            [ '/turven/barinvoer', '#' => 'w1-tab2'],
            [ 
                'class' => 'btn-lg btn-danger namen',
                'data' => [
                    'confirm' => 'Je wijzegingen zijn niet opgeslagen',
                ],
            ]
        );?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
