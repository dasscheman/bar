<?php

/* @var $this yii\web\View */

use dosamigos\highcharts\HighCharts;
use yii\helpers\Html;

$this->title = 'Overzichten';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        foreach ($rendement_year as $item) {
            $plotlines[] = [
                'value' => $item['data'],
                'color' => 'green',
//                'dashStyle' => 'shortdash',
                'width' => 1,
                'label' => [
                    'text' => $item['name'],
                ]
            ];
        }


        echo HighCharts::widget([
            'clientOptions' => [
                'chart' => [
                        'type' => 'spline',
                'height' => '800px',
                ],
                'plotOptions' => [

                    'spline' => [
                      'marker' => [
                          'enabled' => true
                      ]
                    ]
                ],
                'title' => [
                     'text' => 'Totaal overzicht'
                     ],
                'xAxis' => [
                    'type' => 'datetime',
                    'title' => [
                        'text' => 'Maand'
                    ]
                ],
                'yAxis' => [
                    'title' => [
                        'text' => '%'
                    ],
                    'plotLines' => $plotlines,
                ],
                'tooltip' => [
                    'headerFormat' => '<b>{series.name}</b><br>',
                    'pointFormat' => '{point.x:%b. %y}: {point.y:.2f} %'
                ],
                'series' => $series
            ]
        ]);

        ?>
    </p>
</div>
