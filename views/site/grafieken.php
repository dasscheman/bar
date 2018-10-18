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
        echo Html::a('3 Maanden', ['/site/assortiment', 'assortiment_id' => $assortiment_id, 'aantal_maanden' => 3], ['class'=>'btn btn-primary namen']);
        echo Html::a('6 Maanden', ['/site/assortiment', 'assortiment_id' => $assortiment_id, 'aantal_maanden' => 6], ['class'=>'btn btn-primary namen']);
        echo Html::a('12 Maanden', ['/site/assortiment', 'assortiment_id' => $assortiment_id, 'aantal_maanden' => 12], ['class'=>'btn btn-primary namen']);

        echo HighCharts::widget([
            'clientOptions' => [
                'chart' => [
                        'type' => 'area'
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
                            'zoomType' => 'xy'
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
                    'yAxis' => [[

                        'title' => [
                            'text' => $labels['y_axis']
                        ], ],[
                        'title' => [
                            'text' => '%'
                        ],
                        'max' => 120,
                        'opposite' => true
                    ]
                    ],
                    'series' => $seriesVolume
                ]
            ]);
            ?><i>Drank dat over de datum is geraakt wordt niet meegeteld in het rendement </i> <?php
        }
        ?>
    </p>
</div>
