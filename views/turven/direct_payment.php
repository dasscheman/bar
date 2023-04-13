<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\models\User;


/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::encode('Nieuwe turven voor ' . $model->username) ?>
    </div>
    <div class="panel-body">
        <?php

        echo $this->render('/_plain_alert');
        if ($model->limitenControleren()) {
            foreach ($prijslijstDataProvider->getModels() as $item) {
                if (isset($count[$item->prijslijst_id])) {
                    $labelName = $item->getEenheid()->one()->name . ' <span class="bold-red">' . $count[$item->prijslijst_id] . '</span>';
                } else {
                    $labelName = $item->getEenheid()->one()->name;
                }
                echo Html::a(
                    $labelName,
                    [
                        'directbetalen',
                        'prijslijst_id' => $item->prijslijst_id,
                        'count' => $count,
                        'user_id' => $model->id,
                        'actie' => 'toevoegen',
                        'tab' => 'w2-tab1'
                    ],
                    ['class' => 'btn-lg btn-info namen']
                );
            }
        } ?>
    </div>
</div>
<?php

echo Html::a(
    'Betalen',
    [
        'directbetalen',
        'count' => $count,
        'user_id' => $model->id,
        'actie' => 'opslaan',
        'tab' => $tab
    ],
    [
        'class' => 'btn-lg btn-success',
        !empty($count)?'':'disabled' => 'disabled'
    ]
);

echo Html::a(
    'Annuleren',
    [
        'directpayment',
        '#' => $tab
    ],
    [
        'class' => 'btn-lg btn-danger',
        'data' => [
            'confirm' => 'Je turven zijn niet opgeslagen',
        ],
    ]
);
