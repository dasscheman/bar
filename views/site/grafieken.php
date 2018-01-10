<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use dosamigos\highcharts\HighCharts;

$this->title = 'Overzichten';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    foreach ($assortimentItems as $item) {
        echo Html::a(
            $item->merk,
            [ '/site/grafieken', 'merk' => $item->merk ],
            [ 'class' => 'btn-lg btn-info namen' ]
        );
    } ?>
    <p>
        <?php
        echo HighCharts::widget([
            'clientOptions' => [
                'chart' => [
                        'type' => 'column'
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
                'series' => [
                    ['name' => 'Inkomsten', 'data' => array_values($inkomsten)],
                    ['name' => 'Uitgaven', 'data' => array_values($uitgaven)]
                ]
            ]
        ]);

        if(!empty($volume_verkoop) && !empty($volume_inkoop)) {

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
