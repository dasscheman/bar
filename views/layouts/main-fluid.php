<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Assortiment;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    NavBar::begin([
        'brandLabel' => 'Bison bar',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->can('onlinebetalen') ? ['label' => 'Betalen', 'url' => ['/mollie/betaling']]:'',
            Yii::$app->user->can('beheerder') ? ['label' => 'Beheerder',
                'items' => [
                    [
                        'label' => 'Overzicht gebruikers',
                        'url'=>['/user/admin/index'],
                    ],
                    [
                        'label' => 'Bar beheer',
                        'url'=>['/assortiment/index'],
                    ],
//                    [
//                        'label' => 'Turven',
//                        'url'=>['/turven/index'],
//                    ],
                ]
            ]:'',
//          ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Cach Flush', 'url' => ['/site/cache-flush']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/user/security/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => '', // isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; BisonBar <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
