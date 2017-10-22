<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\turven
 */

use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Assortiment;
use app\models\Turflijst;

?>

<div class="assortiment-form">
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
    ]);   
    echo $form->field($model, 'consumer_user_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
            'id' => 'consumer_user_id',
        ],
    ]);
    echo $form->field($model, 'assortiment_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Assortiment::find()->all(), 'assortiment_id', 'name'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer assortiment item'),
            'id' => 'assortiment_id',
        ],
    ]);
    echo $form->field($model, 'turflijst_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Turflijst::find()->all(), 'turflijst_id', 'volgnummer'),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer turflijst'),
            'id' => 'turflijst_id',
        ],
    ]);
    echo $form->field($model, 'status')->widget(Select2::className(), [
        'data' => $model->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer status turven'),
            'id' => 'status'
        ],
    ]);
// Dit wordt op basisvan turflijst bepaald
//    echo $form->field($model, 'datum')->widget(DatePicker::className(), [
//
//        'model' => $model,
//        'attribute' => 'datum',
//        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
//    //    'value' => '23-Feb-1982',
//        'options'   => [
//            'placeholder' => Yii::t('app', 'Datum'),
//        ],
//        'pluginOptions' => [
//            'autoclose'=>true,
//            'format' => 'yyyy-mm-dd'
//        ]
//    ]);
    echo $form->field($model, 'aantal')->textInput();

    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end(); ?>
</div>