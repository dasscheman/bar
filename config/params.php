<?php

return [
    'adminEmail' => $_ENV['ADMIN_EMAIL'],
    'maskMoneyOptions' => [
        'prefix' => '€ ',
        'suffix' => '',
        'affixesStay' => true,
        'thousands' => ',',
        'decimal' => '.',
        'precision' => 2,
        'allowZero' => true,
        'allowNegative' => true,
    ]
];
