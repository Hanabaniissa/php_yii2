<?php

use app\components\solr\Solr;
use yii\symfonymailer\Mailer;

return [
    'solr' => [
        'class' => Solr::class,
        'protocol' => 'http',
        'host' => 'localhost',
        'port' => '8983',
        'path' => '/solr/',
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
        'retries' => 1,

    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'mailer' => [
        'class' => Mailer::class,
        'viewPath' => '@app/mail',
        'useFileTransport' => true,
    ],
    'queue' => [
        'class' => \yii\queue\redis\Queue::class,
        'redis' => 'redis', // Redis connection component or its config
        'channel' => 'queue', // Queue channel key
    ],
    'support-language' => [
        'class' => 'app\messages\SelectLanguage',
        'supported_languages' => ['en', 'ar']
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => \yii\log\FileTarget::class,
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
];