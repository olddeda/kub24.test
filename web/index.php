<?php

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
use app\components\EnvLoader;
EnvLoader::load();

// Define constants from environment
defined('YII_DEBUG') or define('YII_DEBUG', EnvLoader::getBool('APP_DEBUG', true));
defined('YII_ENV') or define('YII_ENV', EnvLoader::getString('APP_ENV', 'dev'));

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
