<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
$dotenv->load();

require_once(__DIR__.'/../config/debug.php');
$params = require(__DIR__ . '/../config/params.php');

$config = [
    'id' => 'basic',
    'name'=>'Bison Bar',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Europe/Amsterdam',
    'bootstrap' => [
        'log',
        'app\components\Bootstrap',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $_ENV['COOKIE_KEY'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // 'user' => [
        //     'identityClass' => 'app\models\User',
        //     'enableAutoLogin' => true,
        // ],
        'errorHandler' => [
            'maxSourceLines' => 20,
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => $_ENV['EMAIL_TO_FILE'],
            'transport' => require(__DIR__ . '/../config/email.php')
        ],
        'log' => [
            'traceLevel' => $_ENV['YII_DEBUG_LEVEL'],
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'setupdatetime' => [
            'class' => 'app\components\SetupDateTime',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'hostInfo' => $_ENV['URL'],
        ],

//        'authManager' => [
//            'class' => 'dektrium\rbac\RbacWebModule',
//
//            'defaultRoles' => ['guest', 'user'],
//        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'setupdatetime' => [
            'class' => 'app\components\SetupDateTime',
        ],
        'i18n' => [
            'translations' => [
//                'app' => [
//                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@app/messages',
//                ],
                'kvgrid' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
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
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'gridview' =>  [
             'class' => '\kartik\grid\Module'
         ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'params' => $params,
];

if ($_ENV['YII_ENV'] == 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
//        'allowedIPs' => ['127.0.0.1', '::1', '192.168.10.5'],
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
//        'allowedIPs' => ['127.0.0.1', '::1', '192.168.10.5'],
        'allowedIPs' => ['*'],
    ];
}

return $config;
