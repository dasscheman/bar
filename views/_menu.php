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
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/assortiment/index']],
                ['label' => 'Toevoegen', 'url' => ['/assortiment/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Turflijst',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/turflijst/index']],
                ['label' => 'Toevoegen', 'url' => ['/turflijst/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Prijslijst',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/prijslijst/index']],
                ['label' => 'Toevoegen', 'url' => ['/prijslijst/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Turven',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/turven/index']],
                ['label' => 'Toevoegen', 'url' => ['/turven/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Transacties',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/transacties/index']],
                ['label' => 'Toevoegen', 'url' => ['/transacties/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Inkoop',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/inkoop/index']],
                ['label' => 'Toevoegen', 'url' => ['/inkoop/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Facturen',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/factuur/index']],
                ['label' => 'Facturen maken', 'url' => ['/factuur/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Bonnen',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/bonnen/index']],
                ['label' => 'Bon invoeren', 'url' => ['/bonnen/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Betaling typs',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/betaling-type/index']],
                ['label' => 'Betaling type maken', 'url' => ['/betaling-type/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Favorieten',
            'items' => [
                ['label' => 'Overzicht', 'url' => ['/favorieten-lijsten/index']],
                ['label' => 'Favorieten lijst maken', 'url' => ['/favorieten-lijsten/create']],
//                ['label' => 'Something else here', 'url' => '#'],
            ],
        ],
//        [
//            'label' => Yii::t('app', 'Facturen'),
//            'url' => ['/factuur/index'],
//        ],
    ],
]);
