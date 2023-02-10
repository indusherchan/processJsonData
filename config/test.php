<?php

$db = require __DIR__ . '/db.php';
/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'db' => $db,
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],

    'params' => [],
];
