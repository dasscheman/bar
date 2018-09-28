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

<?= $this->render('/_alert') ?>
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
                            'label' => 'Bankrekening',
                            'url' => ['/transacties/bank']
                        ],
                        [
                            'label' => 'Transacties',
                            'url' => ['/transacties/index']
                        ],
                        [
                            'label' => 'Bonnen',
                            'url' => ['/bonnen/index']
                        ],
                        [
                            'label' => 'Facturen',
                            'url' => ['/factuur/index']
                        ],
                        [
                            'label' => 'Betalings Typen',
                            'url' => ['/betaling-type/index']
                        ],
                        [
                            'label' => 'Transacties invoeren',
                            'options' => ['class' =>'nav-pills'],
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Betaling gedaan met pinpas'),
                                    'url' => ['/transacties/create', 'type' => 'pin'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Bankoverschrijving bij van gebruiker'),
                                    'url' => ['/transacties/create', 'type' => 'bankbij_gebruiker'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Izettle pin invoeren'),
                                    'url' => [ '/transacties/create', 'type' => 'izettle_invoer'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Statiegeld ontvangen'),
                                    'url' => [ '/transacties/create', 'type' => 'statiegeld'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Declaratie Invoeren'),
                                    'url' => [ '/transacties/create', 'type' => 'declaratie_invoer'],
                                ],
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Uitbetalingen</li>',
                                [
                                    'label' => Yii::t('app', 'Declaratie Uitbetalen'),
                                    'url' => [ '/transacties/create', 'type' => 'declaratie_uitbetaling'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Izettle uitbetaling'),
                                    'url' => [ '/transacties/create', 'type' => 'izettle_uitbetaling'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Mollie uitbetaling'),
                                    'url' => [ '/transacties/create', 'type' => 'mollie_uitbetaling'],
                                ],

                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Bank en Providers kosten</li>',

                                [
                                    'label' => Yii::t('app', 'Izettle kosten'),
                                    'url' => [ '/transacties/create', 'type' => 'izettle_kosten'],
                                ],
                                [
                                    'label' => Yii::t('app', 'ING kosten'),
                                    'url' => [ '/transacties/create', 'type' => 'ing_kosten'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Mollie kosten'),
                                    'url' => [ '/transacties/create', 'type' => 'mollie_kosten'],
                                ],

                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Algemene transacties</li>',
                                [
                                    'label' => Yii::t('app', 'Transactie toevoegen'),
                                    'url' => [ '/transacties/create'],
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
`
