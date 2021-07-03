<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Assortiment;

use kartik\money\MaskMoney;
use app\models\Bonnen;

/* @var $this yii\web\View */
/* @var $model app\models\Inkoop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inkoop-form">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>
    Hier kun je voorraad toevoegen.
    <br>
    Winkels als de Gepu en Makro geven meestal niet de totaal prijs. Korting en btw worden appart berekend.
    Daarom zijn de velden korting (EUR), korting % en btw % toegevoegd. Voor boodschappen bij de Albert Heijn heb je die velden niet nodig, je kunt ze dan leeglaten.
    Maar ze kunnen handig zijn voor de boodschappen van de groothandel. Deze velden worden niet opgeslagen en zijn alleen bedoelt als  rekenhulp.
    <br>
    LET OP! korting wordt eerst berekend als laatst pas de btw.

    <br><br>
    <?php
    $form = ActiveForm::begin([
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

    echo $form->field($model, 'bon_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(
            Bonnen::find()->orderBy(['bon_id' => SORT_DESC])->all(),
            'bon_id',
            function($model, $defaultValue) {

                return $model['omschrijving']. ' (' . $model->bon_id .')';
            }
        ),
        'options'   => [
            'placeholder' => Yii::t('app', 'Selecteer transactie'),
            'id' => 'transacties_id',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    echo $form->field($model, 'volume')->textInput();

    echo $form->field($model, 'aantal')->textInput();

    echo $form->field($model, 'totaal_prijs')->widget(MaskMoney::classname());
    echo $form->field($model, 'korting_bedrag')->widget(MaskMoney::classname());
    echo $form->field($model, 'korting_procent')->textInput();
    echo $form->field($model, 'btw')->textInput();

    echo $form->field($model, 'type')->widget(Select2::className(), [
        'data' => $model->getTypeOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Selecteer type inkoop'),
            'id' => 'status'
        ],
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
