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
                            'label' => 'Prijslijst',
                            'url' => ['/prijslijst/index']
                        ],
                        [
                            'label' => 'Aanpassingen',
                            'options' => ['class' =>'nav-pills'],
                            'items' => [
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Assortiment</li>',
                                [
                                    'label' => Yii::t('app', 'Assortiment toevoegen'),
                                    'url' => ['/assortiment/create'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Eenheid toevoegen'),
                                    'url' => ['/eenheid/create'],
                                ],
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Voorraden</li>',
                                [
                                    'label' => Yii::t('app', 'Voorraad toevoegen'),
                                    'url' => [ '/inkoop/create'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Afschrijving toevoegen'),
                                    'url' => [ '/afschrijving/create'],
                                ],
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Kosten</li>',
                                [
                                    'label' => Yii::t('app', 'Kosten Invoeren'),
                                    'url' => [ '/kosten/create'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Prijslijst toevoegen'),
                                    'url' => [ '/prijslijst/create'],
                                ]
                            ],
                        ]
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
