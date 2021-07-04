<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use \app\models\BetalingType;

/**
 * @var $this yii\web\View
 * @var $model app\models\Transacties
 */

$this->beginContent('../views/_beheer2.php');
    $this->title = BetalingType::findOne(Yii::$app->request->get('type_id'))->omschrijving . ' Toevoegen';
    echo  $this->render('_form', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);
$this->endContent();
