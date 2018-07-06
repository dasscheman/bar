<?php

/*
 * Bar App de Bison by daan@biolgenkantoor.nl
 */

use yii\helpers\Html;
use kartik\detail\DetailView

/* @var $this yii\web\View */
/* @var $model app\models\Assortiment */;

$this->beginContent('../views/_beheer1.php');
?>

<div class="panel-body">
    <?php 
    echo $this->render('/_alert'); ?>
    <div class="view">
        <?php
        echo Html::a(
            Yii::t('app', 'Bewerken'),
            [ 'update', 'id' => $model->assortiment_id ],
            [ 'class' => 'btn btn-success' ]
        );
        echo Html::a(
            Yii::t('app', 'Delete'),
            [ 'delete', 'id' => $model->assortiment_id ],
            [ 'class' => 'btn btn-danger', 'data-method'=>'post' ]
        ); ?>
        <table class="table">
            <?php
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'assortiment_id',
                    'name',
                    'merk',
                    'soort',
                    'alcohol',
                    'change_stock_auto',
                    'volume',
                    'status',
                    'created_at',
                    'created_by',
                    'updated_at',
                    'updated_by',
                ],
            ])
            ?>
        </table>
    </div>
    <div class="panel-heading">
        <?= Html::encode('Prijzen') ?>
    </div>
    <?php
        echo $this->render('/prijslijst/index', [
            'searchModel' => $searchModelPrijslijst,
            'dataProvider' => $dataProviderPrijslijst,
        ]); ?>
    <div class="panel-heading">
        <?= Html::encode('Voorraad') ?>
    </div>
    <?php
        echo $this->render('/inkoop/index', [
            'searchModel' => $searchModelInkoop,
            'dataProvider' => $dataProviderInkoopVoorraad,
        ]); ?>
    <div class="panel-heading">
        <?= Html::encode('Voorraad history') ?>
    </div>
    <?php
        echo $this->render('/inkoop/index', [
            'searchModel' => $searchModelInkoop,
            'dataProvider' => $dataProviderInkoopAll,
        ]);
    ?>
</div>
<?php
$this->endContent();
