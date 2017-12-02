<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Favorieten */

$this->title = 'Create Favorieten';
$this->params['breadcrumbs'][] = ['label' => 'Favorietens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="favorieten-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
