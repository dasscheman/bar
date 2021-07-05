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
use app\models\BetalingType;

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
                            'label' => 'Bankrekening transacties',
                            'url' => ['/transacties/bank']
                        ],
                        [
                            'label' => 'Tegoeden',
                            'url' => ['/transacties/index']
                        ],
                        [
                            'label' => 'Kosten',
                            'url' => ['/kosten/index']
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
                            'label' => 'Transacties invoeren',
                            'options' => ['class' =>'nav-pills'],
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Betaling gedaan met pinpas'),
                                    'url' => ['/transacties/create', 'type_id' => BetalingType::getPinId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Bankoverschrijving bij van gebruiker'),
                                    'url' => ['/transacties/create', 'type_id' => BetalingType::getBankBijId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Izettle pin invoeren'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getIzettleInvoerId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Statiegeld ontvangen'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getStatiegeldId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Declaratie Invoeren'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getDeclaratieInvoerId()],
                                ],
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Uitbetalingen</li>',
                                [
                                    'label' => Yii::t('app', 'Declaratie Uitbetalen'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getDeclaratieUitbetaalsId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Izettle uitbetaling'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getIzettleUitbetalingId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Mollie uitbetaling'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getMollieUitbetalingId()],
                                ],

                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Bank en Providers kosten</li>',

                                [
                                    'label' => Yii::t('app', 'Izettle kosten'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getIzettleKosotenId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'ING kosten'),
                                    'url' => [ '/transacties/create', 'type_id' =>BetalingType::getIngKostenId()],
                                ],
                                [
                                    'label' => Yii::t('app', 'Mollie kosten'),
                                    'url' => [ '/transacties/create', 'type_id' => BetalingType::getMollieKostenId()],
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

        <?php if( file_exists(Yii::$app->getViewPath() .'/' . Yii::$app->controller->id . '/_help.php') ) { ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo $this->render(Yii::$app->controller->id . '/_help'); ?>
                </div>
            </div>
        <?php } ?>
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
