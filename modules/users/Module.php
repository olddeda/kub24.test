<?php

namespace app\modules\users;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\GroupUrlRule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
        $this->registerMigrations();
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application && $app->hasModule('users')) {
            $rules = [
                '' => 'default/index',
                '<action:\w+>/<id:\d+>' => 'default/<action>',
                '<action:\w+>' => 'default/<action>',
            ];

            $configUrlRule = [
                'prefix' => 'users',
                'rules' => $rules,
                'routePrefix' => 'users',
            ];

            $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);
        }
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['users*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/users/messages',
            'fileMap' => [
                'users' => 'user.php',
                'users.enum' => 'enum.php',
            ],
        ];
    }

    public function registerUrlRules()
    {
        if (Yii::$app instanceof \yii\web\Application) {
            Yii::$app->urlManager->addRules([
                'users/<action:\w+>/<id:\d+>' => 'users/default/<action>',
                'users/<action:\w+>' => 'users/default/<action>',
                'users' => 'users/default/index',
            ]);
        }
    }

    public function registerMigrations()
    {
        if (Yii::$app instanceof \yii\console\Application) {
            $migrationPath = $this->getBasePath() . DIRECTORY_SEPARATOR . 'migrations';
            if (is_dir($migrationPath)) {
                if (!isset(Yii::$app->controllerMap['migrate'])) {
                    Yii::$app->controllerMap['migrate'] = [
                        'class' => 'yii\console\controllers\MigrateController',
                        'migrationPath' => ['@app/migrations'],
                    ];
                }
                if (!isset(Yii::$app->controllerMap['migrate']['migrationPath'])) {
                    Yii::$app->controllerMap['migrate']['migrationPath'] = ['@app/migrations'];
                } elseif (is_string(Yii::$app->controllerMap['migrate']['migrationPath'])) {
                    Yii::$app->controllerMap['migrate']['migrationPath'] = [Yii::$app->controllerMap['migrate']['migrationPath']];
                }
                Yii::$app->controllerMap['migrate']['migrationPath'][] = $migrationPath;
            }
        }
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('users', $message, $params, $language);
    }
}
