<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */


/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Assortiment $searchModel
 */

$this->beginContent('../views/_beheer1.php');
        echo $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
$this->endContent();
