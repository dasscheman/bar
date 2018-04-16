<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\money\MaskMoney;

//use yii\widgets\DetailView;

use kartik\detail\DetailView;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
 */

$this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'factuur_id',
        'naam',
        'verzend_datum',
        'pdf',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ],
]); ?>
            <div class="panel-heading">
                <?= Html::encode('Transacties overzicht') ?>
            </div>
<?php
    echo $this->render('_transacties_index', [
        'searchModel' => $searchModelTransacties,
        'dataProvider' => $dataProviderTransacties,
        'user' => $user
    ]);
?>
    <div class="panel-heading">
        <?= Html::encode('Turven overzicht') ?>
    </div>
<?php 
        
    echo $this->render('_turven_index', [
        'searchModel' => $searchModelTurven,
        'dataProvider' => $dataProviderTurven,
        'user' => $user
    ]);

$this->endContent();
