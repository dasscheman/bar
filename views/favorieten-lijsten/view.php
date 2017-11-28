<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FavorietenLijsten */

$this->title = $model->favorieten_lijsten_id;
$this->params['breadcrumbs'][] = ['label' => 'Favorieten Lijstens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="favorieten-lijsten-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->favorieten_lijsten_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->favorieten_lijsten_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'favorieten_lijsten_id',
            'omschrijving',
            'user_id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
