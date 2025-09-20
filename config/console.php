<?php

use app\components\EnvLoader;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'kub24-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'auth', 'users', 'products'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'modules' => [
        'auth' => [
            'class' => 'app\modules\auth\Module',
        ],
        'users' => [
            'class' => 'app\modules\users\Module',
        ],
        'products' => [
            'class' => 'app\modules\products\Module',
        ],
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
            'currencyCode' => 'RUB',
            'locale' => 'ru-RU',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => 'redis',
            'keyPrefix' => 'app:',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => EnvLoader::getString('REDIS_HOST', 'localhost'),
            'port' => EnvLoader::getInt('REDIS_PORT', 6379),
            'database' => EnvLoader::getInt('REDIS_DATABASE', 0),
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'email*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'email' => 'email.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
        ],
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
                '@app/modules/users/migrations',
                '@app/modules/products/migrations',
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
