<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

/**
 * @var \yii\web\View $this
 * @var \dektrium\user\models\User $user
 * @var string $content
 */

//$this->title = Yii::t('user', 'Update user account');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<?= $this->render('_menu') ?>

<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= Nav::widget([
                    'options' => [
                        'class' => 'nav-pills nav-stacked',
                    ],
                    'items' => [
                        [
                            'label' => 'Assortiment',
                            'url' => ['/assortiment/index']
                        ],
                        [
                            'label' => 'Eenheid',
                            'url' => ['/eenheid/index']
                        ],
                        [
                            'label' => 'Inkoop',
                            'url' => ['/inkoop/index']
                        ],
                        [
                            'label' => 'Afschrijving',
                            'url' => ['/afschrijving/index']
                        ],
                        [
                            'label' => 'Kosten',
                            'url' => ['/kosten/index']
                        ],
                        [
                            'label' => 'Prijslijst',
                            'url' => ['/prijslijst/index']
                        ],

                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo $content ?>
            </div>
        </div>
    </div>
</div>
