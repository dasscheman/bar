<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

/**
 * @var $this  yii\web\View
 * @var $model app\models\Factuur
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="factuur-form">
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'enableAjaxValidation'   => true,
    ]);

    echo $form->field($model, 'naam')->textarea();
    echo $form->field($model, 'verzend_datum')->textarea();
    echo $form->field($model, 'pdf')->textarea();
    echo Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success btn-block']);

    ActiveForm::end() ?>
</div>