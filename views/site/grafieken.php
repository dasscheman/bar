<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use dosamigos\highcharts\HighCharts;

$this->title = 'Overzichten';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        echo HighCharts::widget([
            'clientOptions' => [
                'chart' => [
                        'type' => 'column'
                ],
                'title' => [
                     'text' => 'Fruit Consumption'
                     ],
                'xAxis' => [
                    'categories' => $maanden
                ],
                'yAxis' => [
                    'title' => [
                        'text' => 'Fruit eaten'
                    ]
                ],
                'series' => [
                    ['name' => 'Inkomsten', 'data' => $inkomsten],
                    ['name' => 'Uitgaven', 'data' => $uitgaven]
                ]
            ]
        ]);?>
    </p>
</div>
