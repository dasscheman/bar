<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\Transacties
 */

use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\User;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use app\models\BetalingType;
use kartik\widgets\FileInput;

?>

<div class="transacties-form">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>

    <?php

    switch (Yii::$app->request->get('type')) {
        case 'pin':
            echo 'Hier moeten betalingen die zijn gedaan met de pinpas ingevoerd worden. Een bon is verplicht.';
            break;
        case 'bankbij_gebruiker':
            echo 'Het gaat om overschrijvingen die een gebruiker van zijn eigen rekening heeft gemaakt.
            Het het gaat niet om de uitbetalingen die gemaakt zijn door Mollie of Izettle.';
            break;
        case 'izettle_invoer':
            echo 'Dit gaat om de pin betaling die een gebruiker heeft gedaan op het pin apparaat.';
            break;
        case 'statiegeld':
            echo 'Als iemand statieflessen terug heeft gebracht, dan kan hij dat geld houden.
            Het bedrag van het statiegeld moet hier ingevoerd worden, dan wordt het verekend met de eerst voglende rekening.';
            break;
        case 'declaratie_invoer':
            echo 'Als iemand inkopen heeft gedaan en zelf voorgeschoten heeft, dan kan dat hier ingevoerd worden.
            Het is verplicht om een bon in te voeren.
            De declaratie wordt verrekend met de rekening van de gebruiker. Als de gebruiker het geld terug wil dan kan dat.
            Het bedrag kan gewoon overgemaakt worden en kan dan ingevoerd worden als "Declaratie uitbetalen".
            Een declaratie die uitbetaald wordt, wordt ook automatisch weer met de rekening van de gebruiker verrekend.';
            break;
        case 'declaratie_uitbetaling':
            echo 'Als iemand een declaratie heeft gedaan, en het hele of gedeeltelijke bedrag terug wil hebben,
            dan kan de bank overschrijving hiet toegevoegd worden. De "Declaratie invoer" moet gelinkt worden.
            Want die heeft de orginele bon van de declaratie. Als er meerdere declaratie zijn ingevoerd,
            kunnen er ook meerdere gelinkt worden.';
            break;
        case 'izettle_uitbetaling':
            echo 'Izettle doet maandelijks een uitbetaling. Het totale bedrag kan hier ingevoerd worden.
            Verder moeten de Izettle invoeren die hiermee uitbetaald worden, gelinkt worden.
            Omdat er meerdere transacties in 1 keer uitbetaald kunnen worden, hoeft er geen naam toegevoegd te worden.
            Verder moet een uitdraai van Izettle met een overzicht toegevoegd worden.';
            break;
        case 'mollie_uitbetaling':
            $transactionsArray = $modelTransacties->getTransactionsArray();
            echo 'Mollie doet maandelijks een uitbetaling. Het totale bedrag wat op de bankrekening bijgescreven wordt 
            moet hier ingevoerd worden. Verder moeten de Ideal betalingen die hiermee uitbetaald worden, gelinkt worden (dit kan ook nog later).
            <br>
            De PDF uitdraai van Mollie met een overzicht moet hier toegevoegd worden. Je kunt hier ook nog de kosten invoeren die Mollie rekent.
            Er wordt dan automatisch een kostenpost en een bon gemaakt.';
            break;
        case 'izettle_kosten':
            echo 'De kosten die Izettle rekent voor hun diensten
            De transactie van de uitbetaling moet gelinkt worden, want die heeft een uitdraai met ook de kosten';
            break;
        case 'ing_kosten':
            echo 'De kosten die de ING rekent voor hun diensten
            Een afschrift invoeren is verplicht.';
            break;
        case 'mollie_kosten':
            echo 'De kosten die Mollie rekent voor hun diensten
            De transactie van de uitbetaling moet gelinkt worden, want die heeft een uitdraai met ook de kosten';
            break;
        default:
            echo 'Transactie toevoegen';
    }
    ?> </br> </br> <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => false,
        'id'   => 'bonnen-form',
        'options'=> ['enctype'=>'multipart/form-data'],
    ]);

    if (in_array(Yii::$app->request->get('type'), ['bankbij_gebruiker', 'izettle_invoer', 'statiegeld', 'declaratie_invoer', 'declaratie_uitbetaling'])) {
        echo $form->field($modelTransacties, 'transacties_user_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
            'options'   => [
                'placeholder' => Yii::t('app', 'Selecteer gebruiker'),
                'id' => 'transacties_user_id',
            ],
        ]);
    }
    if(Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['pin'])) {
        echo $form->field($modelTransacties, 'omschrijving')->textInput();
    }
    if (Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['pin', 'declaratie_invoer', 'izettle_uitbetaling', 'mollie_uitbetaling', 'ing_kosten'])) {
        // Usage with ActiveForm and model
        echo $form->field($modelBonnen, 'image_temp', ['enableClientValidation' => true])->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showCaption' => false,
                'showUpload' => false
            ]
        ]);
        if($modelBonnen->image != null) {
            echo Html::encode('Huidige bon: ' . $modelBonnen->image);
        }
    }
    if (Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['pin', 'declaratie_invoer', 'ing_kosten'])) {
        echo $form->field($modelBonnen, 'soort')->widget(Select2::className(), [
            'data' => $modelBonnen->getSoortOptions(),
            'options' => [
                'placeholder' => Yii::t('app', 'Selecteer soort betaling'),
                'id' => 'soort'
            ],
        ]);
    }

    if (Yii::$app->request->get('type') == null ||
        in_array(Yii::$app->request->get('type'), ['declaratie_uitbetaling', 'izettle_uitbetaling', 'mollie_uitbetaling', 'izettle_kosten', 'mollie_kosten'])) {
        $transactionsArray = $modelTransacties->getTransactionsArray();

        if(Yii::$app->request->get('type') == 'mollie_uitbetaling') {
            $transactionsArray = $modelTransacties->getTransactionsArray([BetalingType::getIdealTerugbetalingId(), BetalingType::getIdealId()],
                [\app\models\Transacties::MOLLIE_STATUS_paid]);
        }
        echo $form->field($modelTransacties, 'all_related_transactions')->widget(Select2::classname(), [
            'name' => 'all_related_transactions',
            'value' => $modelTransacties->all_related_transactions,
            'id' => $modelTransacties->transacties_id,
            'data' => $transactionsArray,
            'options' => [
                'placeholder' => 'Filter as you type ...',
                'id' => $modelTransacties->transacties_id,
                'class' => "form-control",
                'multiple' => true,
            ],
            'pluginOptions' => [
                'tags' => true,
            ]
        ]);
    }

    echo $form->field($modelTransacties, 'bedrag')->widget(MaskMoney::classname());
    if (Yii::$app->request->get('type') == 'mollie_uitbetaling') {
        echo $form->field($modelTransacties, 'bedrag_kosten')->widget(MaskMoney::classname());
    }

    if (Yii::$app->request->get('type') == null) {
        echo $form->field($modelTransacties, 'type_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(BetalingType::find()->all(), 'type_id', 'omschrijving'),
            'options'   => [
                'placeholder' => Yii::t('app', 'Selecteer betaling type'),
                'id' => 'type_id',
            ],
        ]);
    }

    echo $form->field($modelTransacties, 'datum')->widget(DatePicker::className(), [
        'model' => $modelTransacties,
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
    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end(); ?>
</div>
