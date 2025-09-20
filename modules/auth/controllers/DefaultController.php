<?php

declare(strict_types=1);

namespace app\modules\auth\controllers;

use Yii;

use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use app\modules\auth\models\LoginForm;
use app\modules\auth\models\SignupForm;
use app\modules\auth\models\PasswordResetRequestForm;
use app\modules\auth\models\ResetPasswordForm;
use app\components\services\EmailService;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->login()) {
                return $this->goBack();
            }
            else {
                Yii::$app->session->setFlash('error', Yii::t('auth', 'error.login_failed'));
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($user = $model->signup()) {
                    $emailService = new EmailService();
                    $emailService->sendWelcomeEmail($user);
                    
                    if (Yii::$app->user->login($user)) {
                        Yii::$app->session->setFlash('success', Yii::t('auth', 'message.signup_success'));
                        return $this->goHome();
                    }
                }
            }
            Yii::$app->session->setFlash('error', Yii::t('auth', 'error.signup_failed'));
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionRequestPasswordReset(): Response|string
    {
        $model = new PasswordResetRequestForm();
        
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success', Yii::t('auth', 'message.password_reset_sent'));

                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('auth', 'error.password_reset_failed'));
                }
            }
        }

        return $this->render('request-password-reset', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionResetPassword(string $token): Response|string
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->goHome();
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->resetPassword()) {
                    Yii::$app->session->setFlash('success', Yii::t('auth', 'message.password_reset_success'));

                    return $this->redirect(['login']);
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('auth', 'error.password_reset_save_failed'));
                }
            }
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
