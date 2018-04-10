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

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
 */
?>

<?php
    $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]);

    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]);

    echo $form->field($profile, 'name');
    echo $form->field($profile, 'voornaam');
    echo $form->field($profile, 'tussenvoegsel');
    echo $form->field($profile, 'achternaam');
    echo $form->field($profile, 'public_email');
    echo $form->field($profile, 'limit_hard')->widget(MaskMoney::classname());
    echo $form->field($profile, 'limit_ophogen')->widget(MaskMoney::classname());

?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
