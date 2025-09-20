<?php

use app\components\EnvLoader;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'kub24-test-app',
    'name' => EnvLoader::getString('APP_NAME', 'Kub24 Test Application'),
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\components\LanguageSelector', 'auth', 'users', 'products'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en',
    'sourceLanguage' => 'en',
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
        'request' => [
            'cookieValidationKey' => EnvLoader::getString('COOKIE_VALIDATION_KEY', 'your-secret-key-here'),
        ],
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
        'user' => [
            'identityClass' => 'app\modules\users\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => array_merge([
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => EnvLoader::getBool('MAIL_USE_FILE_TRANSPORT', EnvLoader::getString('APP_ENV', 'dev') === 'dev'),
        ], EnvLoader::getBool('MAIL_USE_FILE_TRANSPORT', EnvLoader::getString('APP_ENV', 'dev') === 'dev') ? [] : [
            'transport' => [
                'dsn' => EnvLoader::getString('MAIL_DSN', '') ?: sprintf(
                    'smtp://%s:%s@%s:%d',
                    EnvLoader::getString('MAIL_USERNAME', ''),
                    EnvLoader::getString('MAIL_PASSWORD', ''),
                    EnvLoader::getString('MAIL_HOST', 'localhost'),
                    EnvLoader::getInt('MAIL_PORT', 587)
                ),
            ],
        ]),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'auth/default/login',
                'logout' => 'auth/default/logout',
                'signup' => 'auth/default/signup',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'email*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'email' => 'email.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
