<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\turven
 */

use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Eenheid;
use app\models\Turflijst;

?>

<div class="turven-form">
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
        'type' => ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo $form->field($models[0], '[0]consumer_user_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'id' => '0consumer_user_id',
        ],
    ]);

    echo $form->field($models[0], '[0]turflijst_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Turflijst::find()->orderBy(['volgnummer'=>SORT_DESC])->all(), 'turflijst_id', 'volgnummer'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer turflijst'),
            'id' => '0turflijst_id',
        ],
    ]);

    echo $form->field($models[0], '[0]status')->widget(Select2::className(), [
        'data' => $models[0]->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer status turven'),
            'id' => '0status'
        ],
    ]);

    foreach ($models as $index => $model) { ?>
        <div class="form-group">
            <?php echo Html::activeLabel($model, "[$index]eenheid_id", ['class'=>'col-sm-2 control-label']) ?>
            <div class="col-sm-5">
                <?php echo $form->field($model, "[$index]eenheid_id", ['showLabels'=>false])->widget(Select2::className(), [
                    'data' => ArrayHelper::map(Eenheid::find()->all(), 'eenheid_id', 'name'),
                    'options'   => [
                        'placeholder' => Yii::t('app', 'Selecteer eenheid item'),
                        'id' => $index . 'eenheid_id',
                    ],
                ]); ?>
            </div>
            <div class="col-sm-5">
                <?php echo $form->field($model, "[$index]aantal", ['showLabels'=>false])->textInput(); ?>
            </div>
        </div>
        <?php
    }
    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);
    ActiveForm::end(); ?>
</div>
