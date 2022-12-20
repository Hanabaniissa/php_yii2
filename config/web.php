<?php

use app\components\JwtValidationData;
use yii\helpers\ArrayHelper;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$commonConfig = require __DIR__ . '/common_config.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@upload' => "/web/upload/"
    ],
    'components' => ArrayHelper::merge($commonConfig, [
        'request' => [
            'cookieValidationKey' => 'aofmaqfomadsofmafomom',
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => 'HANA',
            'jwtValidationData' => JwtValidationData::class,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
        ],
    ]),
    'modules' => [
        'api' => [
            'class' => 'app\\modules\\api\\Module',
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}


return $config;
