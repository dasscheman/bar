<?php

/*
 * Bar App de BisonS
 */

use yii\bootstrap\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label' => 'Assortiment',
            'url' => ['/assortiment/index']
        ],
        [
            'label' => 'Transacties',
            'url' => ['/transacties/index']
        ],
        [
            'label' => 'Turven',
            'url' => ['/turven/index']
        ],
        [
            'label' => 'Overige',
            'url' => ['/favorieten-lijsten/index']
        ],
    ],
]);
