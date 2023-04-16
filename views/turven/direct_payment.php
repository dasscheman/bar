<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use app\models\Transacties;
use app\models\TurvenSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use kartik\detail\DetailView;
use app\models\User;


/* @var $this yii\web\View */
/* @var $model app\models\Turven */
?>
<div class="row">
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode('Nieuwe turven voor ' . $model->username) ?>
            </div>
            <div class="panel-body">
                <?php

                echo $this->render('/_plain_alert'); ?>

                <div id="alert-info" class="alert fade in kv-alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Let op, voor qr-betalingen wordt 30 cent extra in rekening gebracht ivm met iDEAL kosten.
                </div>
                <?php
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
                                'tabIndex' => '2'
                            ],
                            ['class' => 'btn-lg btn-info namen']
                        );
                    }
                } ?>
            </div>
        </div>
        <?php echo Html::a(
            'Betalen',
            [
                'directbetalen',
                'count' => $count,
                'user_id' => $model->id,
                'actie' => 'opslaan',
            ],
            [
                'class' => 'btn-lg btn-success',
                !empty($count)?'':'disabled' => 'disabled'
            ]
        );

        echo Html::a(
            'Annuleren',
            [
                'directbetalen',
            ],
            [
                'class' => 'btn-lg btn-danger',
                'data' => [
                    'confirm' => 'Je turven zijn niet opgeslagen',
                ],
            ]
        );
        ?>
        <br>
        <br>
        <?php
            $query = Transacties::find()
                    ->where(['=', 'transacties_user_id',  $_ENV['BAR_ACCOUNT']])
                    ->where(['=', 'type_id',  \app\models\BetalingType::getDirecteBetaling()]);

            $searchModelTransactie = new ActiveDataProvider([
                'query' => $query,
                'sort'=> ['defaultOrder' => [
                    'datum'=>SORT_DESC]],
                'pagination' => ['pageSize' => 5],
            ]);

            echo $this->render('/transacties/index_plain', [
                'dataProvider' => $searchModelTransactie
            ]);
        ?>

    </div>
</div>
