<?php

use app\components\EnvLoader;

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        EnvLoader::getString('DB_HOST', 'localhost'),
        EnvLoader::getInt('DB_PORT', 5432),
        EnvLoader::getString('DB_NAME', 'kub24_test')
    ),
    'username' => EnvLoader::getString('DB_USERNAME', 'postgres'),
    'password' => EnvLoader::getString('DB_PASSWORD', 'password'),
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql' => 'yii\db\pgsql\Schema',
    ],
    'enableSchemaCache' => EnvLoader::getBool('SCHEMA_CACHE_ENABLED', true),
    'schemaCacheDuration' => EnvLoader::getInt('SCHEMA_CACHE_DURATION', 3600),
    'schemaCache' => 'cache',
];
