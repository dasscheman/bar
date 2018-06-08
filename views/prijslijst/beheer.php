<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */


/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */

//$this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]);
//$this->beginContent('@web/views/_beheer.php');
$this->beginContent('../views/_beheer1.php');
        echo $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
$this->endContent();
