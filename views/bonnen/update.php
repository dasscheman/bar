<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bonnen */

$this->title = 'Update Bonnen: ' . $model->bon_id;
$this->params['breadcrumbs'][] = ['label' => 'Bonnens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bon_id, 'url' => ['view', 'id' => $model->bon_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bonnen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
