<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/**
/* @var $this yii\web\View
 * @var $model app\models\Transacties
 */

$this->beginContent('../views/_beheer2.php');
    switch (Yii::$app->request->get('type')) {
        case 'declaratie':
            $this->title = 'Declaratie toevoegen';
            echo  $this->render('_form-declaratie', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);
            break;
        case 'bankaf':
            $this->title = 'Bankafschrijving toevoegen';
            echo  $this->render('_form-declaratie', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);
            break;
        case 'bankbij':
            $this->title = 'Bankbijschrijving toevoegen';
            echo  $this->render('_form', ['modelTransacties' => $modelTransacties]);
            break;
        case 'izettle':
            $this->title = 'Izettle pin betaling toevoegen';
            echo  $this->render('_form', ['modelTransacties' => $modelTransacties]);
            break;
        default:
            $this->title = 'Transactie toevoegen';
            echo  $this->render('_form', ['modelTransacties' => $modelTransacties]);
    }
$this->endContent();
