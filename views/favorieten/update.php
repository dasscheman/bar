<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Favorieten */

$this->title = 'Update Favorieten: ' . $model->favorieten_id;
$this->params['breadcrumbs'][] = ['label' => 'Favorietens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->favorieten_id, 'url' => ['view', 'id' => $model->favorieten_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="favorieten-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
