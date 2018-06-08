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
