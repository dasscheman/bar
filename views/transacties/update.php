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

$this->title = $modelTransacties->type->omschrijving . ' bijwerken';

echo  $this->render('_form', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);

$this->endContent();
