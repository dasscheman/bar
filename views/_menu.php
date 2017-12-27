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
            'label' => 'Voorraad',
            'items' => [
                ['label' => 'Actueel', 'url' => ['/inkoop/index-actueel']],
                ['label' => 'History', 'url' => ['/inkoop/index']],
            ],
        ],
        [
            'label' => 'Bonnen',
            'url' => ['/bonnen/index']
        ],
        [
            'label' => 'Assortiment',
            'url' => ['/assortiment/index']
        ],
        [
            'label' => 'Turven',
            'url' => ['/turven/index']
        ],
        [
            'label' => 'Transacties',
            'url' => ['/transacties/index']
        ],
        [
            'label' => 'Facturen',
            'url' => ['/factuur/index']
        ],
        [
            'label' => 'Turflijst',
            'url' => ['/turflijst/index']
        ],
        [
            'label' => 'Prijslijst',
            'url' => ['/prijslijst/index']
        ],
        [
            'label' => 'Betaling typs',
            'url' => ['/betaling-type/index']
        ],
        [
            'label' => 'Favorieten',
            'url' => ['/favorieten-lijsten/index']
        ],
        [
            'label' => 'Toevoegen',
            'items' => [
                ['label' => 'Assortiment toevoegen', 'url' => ['/assortiment/create']],
                ['label' => 'Bon invoeren', 'url' => ['/bonnen/create']],
                ['label' => 'Declaratie toevoegen', 'url' => ['/transacties/create-declaratie']],
                ['label' => 'Transactie toevoegen', 'url' => ['/transacties/create']],
                ['label' => 'Turven toevoegen', 'url' => ['/turven/create']],
                ['label' => 'Facturen genereren', 'url' => ['/factuur/create']],
                ['label' => 'Turflijst Toevoegen', 'url' => ['/turflijst/create']],
                ['label' => 'Voorraad toevoegen', 'url' => ['/inkoop/create']],
                ['label' => 'Prijslijst Toevoegen', 'url' => ['/prijslijst/create']],
                ['label' => 'Betaling type maken', 'url' => ['/betaling-type/create']],
                ['label' => 'Favorieten lijst maken', 'url' => ['/favorieten-lijsten/create']],
            ],
        ],
//        [
//            'label' => Yii::t('app', 'Facturen'),
//            'url' => ['/factuur/index'],
//        ],
    ],
]);
