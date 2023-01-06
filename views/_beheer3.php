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
                            'label' => 'Turven',
                            'url' => ['/turven/index']
                        ],
                        [
                            'label' => 'Turflijst',
                            'url' => ['/turflijst/index']
                        ],

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
