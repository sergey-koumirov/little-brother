<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'someSuperSicretKookiByTheWay',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'POST <controller:[\w-]+>' => '<controller>/create',
                '<controller:[\w-]+>/<id:\d+>/update' => '<controller>/update',
                '<controller:[\w-]+>/<id:\d+>/delete' => '<controller>/delete',
                '<controller:[\w-]+>/<id:\d+>/view'   => '<controller>/view',
                '<controller:[\w-]+>/<id:\d+>/data'   => '<controller>/data',
                '<controller:[\w-]+>/index' => '<controller>/index',                
            ]
        ],      
        'resque' => [
            'class' => '\resque\RResque',
            'server' => 'localhost',     // Redis server address
            'port' => '6379',            // Redis server port
            'database' => 0,             // Redis database number
            'password' => '',            // Redis password auth, set to '' or null when no auth needed
        ],
    ],
    'defaultRoute' => 'analysis/index',
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    // $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
