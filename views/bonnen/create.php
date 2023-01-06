<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/**
/* @var $this yii\web\View
 * @var $model app\models\Factuur
 */

$this->beginContent('../views/_beheer2.php');
    $title = 'Nieuwe transactie toevoegen';
    if(Yii::$app->request->get('type_id') !== null) {
        $title = BetalingType::findOne(Yii::$app->request->get('type_id'))->omschrijving . ' Toevoegen';
    }
    $this->title = $title;
    echo  $this->render('_form', ['model' => $model]);
$this->endContent();

