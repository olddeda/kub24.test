<?php

namespace app\modules\products;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\GroupUrlRule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\products\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
        $this->registerMigrations();
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application && $app->hasModule('products')) {
            // Products routes
            $productsRules = [
                '' => 'default/index',
                '<action:\w+>/<id:\d+>' => 'default/<action>',
                '<action:\w+>' => 'default/<action>',
            ];

            $app->get('urlManager')->rules[] = new GroupUrlRule([
                'prefix' => 'products',
                'rules' => $productsRules,
                'routePrefix' => 'products',
            ]);

            // Categories routes
            $categoriesRules = [
                '' => 'category/index',
                '<action:\w+>/<id:\d+>' => 'category/<action>',
                '<action:\w+>' => 'category/<action>',
            ];

            $app->get('urlManager')->rules[] = new GroupUrlRule([
                'prefix' => 'categories',
                'rules' => $categoriesRules,
                'routePrefix' => 'products',
            ]);
        }
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['products*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/products/messages',
            'fileMap' => [
                'products' => 'product.php',
                'products.category' => 'category.php',
                'products.enum' => 'enum.php',
            ],
        ];
    }

    public function registerUrlRules()
    {
        if (Yii::$app instanceof \yii\web\Application) {
            Yii::$app->urlManager->addRules([
                'products/<action:\w+>/<id:\d+>' => 'products/default/<action>',
                'products/<action:\w+>' => 'products/default/<action>',
                'products' => 'products/default/index',
                
                'categories/<action:\w+>/<id:\d+>' => 'products/category/<action>',
                'categories/<action:\w+>' => 'products/category/<action>',
                'categories' => 'products/category/index',
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
        return Yii::t('products', $message, $params, $language);
    }
}
