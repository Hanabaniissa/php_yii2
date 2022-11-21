<?php

use app\modules\api\Request;

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'components' => [
        'request' => [
            'class' => Request::class,
            'cookieValidationKey' => 'zsfgsrhsfd',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ]
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\\modules\\api\\modules\\v1\\Module',
        ],
    ],

];
return $config;
