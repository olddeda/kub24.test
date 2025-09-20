<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/auth/default/login']);
        }

        return $this->render('index');
    }

    public function actionLogin()
    {
        return $this->redirect(['/auth/default/login']);
    }

    public function actionLanguage($lang)
    {
        $session = Yii::$app->session;
        $session->set('language', $lang);
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
