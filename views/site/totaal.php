<?php

/* @var $this yii\web\View */

use dosamigos\highcharts\HighCharts;
use yii\helpers\Html;

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
                'plotOptions' => [
                    'column' => [
                        'stacking' => 'normal'
                    ]
                ],
                'title' => [
                     'text' => 'Totaal overzicht'
                     ],
                'xAxis' => [
                    'categories' => $maanden
                ],
                'yAxis' => [
                    'title' => [
                        'text' => 'Euro'
                    ]
                ],
                'series' => $series
            ]
        ]);

        if (!empty($volume_verkoop) && !empty($volume_inkoop)) {
            echo HighCharts::widget([
                'clientOptions' => [
                    'chart' => [
                            'type' => 'column'
                    ],
                    'title' => [
                         'text' => 'Overzicht volume'
                         ],
                    'xAxis' => [
                        'categories' => $maanden
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => 'Liter'
                        ]
                    ],
                    'series' => [
                        ['name' => 'Verkoop', 'data' => array_values($volume_verkoop)],
                        ['name' => 'Inkoop', 'data' => array_values($volume_inkoop)]
                    ]
                ]
            ]);
        }
        ?>
    </p>
</div>
