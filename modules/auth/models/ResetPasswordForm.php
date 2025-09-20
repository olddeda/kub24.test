<?php

declare(strict_types=1);

namespace app\modules\auth\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;
use yii\base\Exception;
use app\modules\users\models\User;

class ResetPasswordForm extends Model
{
    public string $password = '';

    private User $_user;

    public function __construct(string $token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(Yii::t('auth', 'error.invalid_token'));
        }

        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException(Yii::t('auth', 'error.invalid_token'));
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'password' => Yii::t('auth', 'field.new_password'),
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     * @throws Exception
     */
    public function resetPassword(): bool
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}
