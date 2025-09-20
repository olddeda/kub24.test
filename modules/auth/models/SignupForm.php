<?php

declare(strict_types=1);

namespace app\modules\auth\models;

use Yii;
use yii\base\Model;
use app\modules\users\models\User;
use app\modules\users\enums\UserStatus;

class SignupForm extends Model
{
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_repeat = '';

    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('auth', 'error.username_taken')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('auth', 'error.email_taken')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('auth', 'error.passwords_mismatch')],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('auth', 'field.username'),
            'email' => Yii::t('auth', 'field.email'),
            'password' => Yii::t('auth', 'field.password'),
            'password_repeat' => Yii::t('auth', 'field.password_repeat'),
        ];
    }

    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->setStatus(UserStatus::ACTIVE);
        
        if ($user->save()) {
            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole('user');
            if ($userRole) {
                $auth->assign($userRole, $user->getId());
            }
            return $user;
        }

        return null;
    }
}
