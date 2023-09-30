<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\components\Bootstrap',
    ],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => $_ENV['EMAIL_TO_FILE'],
            'transport' => require(__DIR__ . '/../config/email.php'),
        ],
        'setupdatetime' => [
            'class' => 'app\components\SetupDateTime',
        ],
        'db' => $db,
        'urlManager' => [
            'baseUrl'         =>  $_ENV['URL'],
            'enablePrettyUrl' => true,
            // Disable index.php
            'showScriptName' => false,
            'class' => 'yii\web\UrlManager',
            'hostInfo' => $_ENV['URL'],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => 'app\models\User',
            ],
            'controllerMap' => [
                'admin' => 'app\controllers\user\AdminController'
            ],
            'admins' => ['daan']
        ],
        'rbac' => 'dektrium\rbac\RbacConsoleModule'
    ],
    'params' => $params,
];

if ($_ENV['YII_ENV']) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
