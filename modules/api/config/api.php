<?php
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'modules' => [
        'v1' => [
            'class' => 'app\\modules\\api\\modules\\v1\\Module',
        ],
    ],
];
return $config;
