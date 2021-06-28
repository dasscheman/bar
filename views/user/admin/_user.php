<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use kartik\money\MaskMoney;

/**
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
 */

echo $form->field($user, 'email')->textInput(['maxlength' => 255]);
echo $form->field($user, 'username')->textInput(['maxlength' => 255]);
echo $form->field($profile, 'name');
echo $form->field($profile, 'public_email');
echo $form->field($profile, 'limit_hard')->widget(MaskMoney::classname());
echo $form->field($profile, 'limit_ophogen')->widget(MaskMoney::classname());


