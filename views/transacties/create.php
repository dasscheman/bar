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
    if (empty($modelBonnen)) {
        echo  $this->render('_form', ['modelTransacties' => $modelTransacties]);
    } else {
        echo  $this->render('_form-declaratie', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);
    }
$this->endContent();
