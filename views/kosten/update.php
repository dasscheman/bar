<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Kosten */

$this->title = 'Update Kosten: ' . $model->kosten_id;
$this->params['breadcrumbs'][] = ['label' => 'Kostens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kosten_id, 'url' => ['view', 'id' => $model->kosten_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kosten-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
