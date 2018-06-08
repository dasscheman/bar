<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BetalingType */

$this->beginContent('../views/_beheer2.php');
    echo  $this->render('_form', ['model' => $model]);
$this->endContent();
