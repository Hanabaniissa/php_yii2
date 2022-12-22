<?php

use app\components\JwtValidationData;
use yii\helpers\ArrayHelper;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$commonConfig = require __DIR__ . '/common_config.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    //TODO
    'bootstrap' => [
        'log',
        'support-language',
        'queue', // The component registers its own console commands
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@upload' => "/web/upload/"
    ],
//    'language' => 'en',

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
            'rules' => [
                Yii::$app->language . '/' . '<controller:\w+>s' => '<controller>/index',
                Yii::$app->language . '/' . '<controller:\w+>/<id:\d+>/<title:\w+>' => '<controller>/view',
                Yii::$app->language . '/' . '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                Yii::$app->language . '/' . '<controller:\w+>/<action:\w+>/<id:\d+>/*' => '<controller>/<action>'
            ],

//            'rules' => ['<language:[\w-]+>/<controller>/<action>' => '<controller>/<action>', '<language:[\w-]+>/<module>/<controller>/<action>' => '<module>/<controller>/<action>',],
////            'enableStrictParsing' => false,
//            'rules' => [
//                [
//                    'class' => \yii\web\UrlRule::class,
//                    'pattern' => [
//                           'language'=>['options'=>'getLanguage']
//                        'en' => Yii::$app->language,
//                        '<language:[a-zA-Z]{2}>' => 'site/index',
////                        'ar'=>
//                    ],
//                    'route' => 'site/index'
//                ],
//            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                    'forceTranslation' => 'true',
                    'fileMap' => [
                        'app' => 'app.php',
//                        'app/error' => 'error.php',
                    ],
                ],
            ],
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
