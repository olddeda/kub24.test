<?php

declare(strict_types=1);

namespace app\modules\auth\models;

use Yii;
use yii\base\Model;
use app\modules\users\models\User;

class LoginForm extends Model
{
    public string $username = '';
    public string $password = '';
    public bool $rememberMe = true;

    private User|false|null $_user = false;

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('auth', 'field.username'),
            'password' => Yii::t('auth', 'field.password'),
            'rememberMe' => Yii::t('auth', 'field.remember_me'),
        ];
    }

    public function validatePassword(string $attribute, ?array $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('auth', 'error.incorrect_credentials'));
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getUser(): ?User
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
