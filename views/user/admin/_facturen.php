<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */

$this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]);
echo $this->render('_facturen_index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'user' => $user
]);
$this->endContent();
