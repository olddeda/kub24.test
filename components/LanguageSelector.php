<?php

namespace app\components;

use Yii;
use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $supportedLanguages = ['en-US', 'ru-RU'];
        $preferredLanguage = null;
        
        if (isset($app->session)) {
            $sessionLanguage = $app->session->get('language');
            if ($sessionLanguage && in_array($sessionLanguage, $supportedLanguages)) {
                $preferredLanguage = $sessionLanguage;
            }
        }
        
        if ($preferredLanguage === null) {
            $preferredLanguage = $app->request->getPreferredLanguage($supportedLanguages);
        }

        if (!in_array($preferredLanguage, $supportedLanguages)) {
            $preferredLanguage = 'en-US';
        }
        
        $app->language = $preferredLanguage;
    }
}
