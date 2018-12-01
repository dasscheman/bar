<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\models\Transacties
 */

$this->beginContent('../views/_beheer2.php');
    switch (Yii::$app->request->get('type')) {
        case 'pin':
            $this->title = 'Betaling met pinpas';
            break;
        case 'bankbij_gebruiker':
            $this->title = 'Bankoverschrijving van gebruiker';
            break;
        case 'izettle_invoer':
            $this->title = 'Invoer van Izettle pin betaling';
            break;
        case 'statiegeld':
            $this->title = 'Statiegeld ontvangen';
            break;
        case 'declaratie_invoer':
            $this->title = 'Declaratie invoeren';
            break;
        case 'declaratie_uitbetaling':
            $this->title = 'Declaratie uitbetalen';
            break;
        case 'izettle_uitbetaling':
            $this->title = 'Izettle uitbetalen';
            break;
        case 'mollie_uitbetaling':
            $this->title = 'Mollie uitbetalen';
            break;
        case 'izettle_kosten':
            $this->title = 'Izettle kosten';
            break;
        case 'ing_kosten':
            $this->title = 'ING kosten';
            break;
        case 'mollie_kosten':
            $this->title = 'Mollie kosten';
            break;
        default:
            $this->title = 'Transactie toevoegen';
    }
echo  $this->render('_form-update', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);

$this->endContent();
