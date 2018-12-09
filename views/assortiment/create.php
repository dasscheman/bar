<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

 use yii\helpers\Html;

 /* @var $this yii\web\View */
 /* @var $model app\models\Inkoop */

 $this->beginContent('../views/_beheer1.php');

 $this->title = 'Assortiment toevoegen';
 echo $this->render('_form', [
     'model' => $model,
 ]);
 $this->endContent();
