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
    $title = 'Nieuwe transactie toevoegen';
    if(Yii::$app->request->get('type_id') !== null) {
        $title = BetalingType::findOne(Yii::$app->request->get('type_id'))->omschrijving . ' Toevoegen';
    }
    $this->title = $title;
    echo  $this->render('_form', ['modelTransacties' => $modelTransacties, 'modelBonnen' => $modelBonnen]);
$this->endContent();
