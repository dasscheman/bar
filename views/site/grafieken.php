<?php

/* @var $this yii\web\View */

use dosamigos\highcharts\HighCharts;
use yii\helpers\Html;

$this->title = 'Overzicht ' . $labels['titel'];
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
                'series' => $seriesGeld
            ]
        ]);

        if (!empty($seriesVolume)) {
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
                         'text' => 'Overzicht volume'
                         ],
                    'xAxis' => [
                        'categories' => $maanden
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => $labels['y_axis']
                        ]
                    ],
                    'series' => $seriesVolume
                ]
            ]);
        }
        ?>
    </p>
</div>
