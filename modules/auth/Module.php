<?php

namespace app\modules\auth;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\auth\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
        $this->registerUrlRules();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['auth*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/auth/messages',
            'fileMap' => [
                'auth' => 'auth.php',
            ],
        ];
    }

    public function registerUrlRules()
    {
        if (Yii::$app instanceof \yii\web\Application) {
            Yii::$app->urlManager->addRules([
                'login' => 'auth/default/login',
                'logout' => 'auth/default/logout',
                'signup' => 'auth/default/signup',
                'forgot-password' => 'auth/default/request-password-reset',
                'reset-password/<token>' => 'auth/default/reset-password',
            ]);
        }
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('auth', $message, $params, $language);
    }
}
