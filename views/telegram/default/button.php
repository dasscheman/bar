<?php
/**
 * @copyright Copyright &copy; Alexandr Kozhevnikov (onmotion)
 * @package yii2-telegram
 * Date: 02.08.2016
 */

/** @var $this \yii\web\View */

use yii\helpers\Html;
?>

<div class="telegram-button">
<?php
echo Html::button(
        '<i class="glyphicon glyphicon-send"></i> <span>' . Yii::t('tlgrm', 'Vraag het Daan') . '</span>',
        [
            'class' => 'btn btn-primary',
            'id' => 'tlgrm-init-btn'
        ]);
?>
</div>
<?php
$options = \yii\helpers\Json::htmlEncode(\Yii::$app->getModule('telegram')->options);
$this->registerJs(<<<JS
var telegramOptions = $options;
JS
, \yii\web\View::POS_BEGIN);

