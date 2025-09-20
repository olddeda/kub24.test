<?php

declare(strict_types=1);

namespace app\modules\auth\models;

use Yii;
use yii\base\Model;
use yii\base\Exception;
use app\modules\users\models\User;
use app\modules\users\enums\UserStatus;
use app\components\services\EmailService;

class PasswordResetRequestForm extends Model
{
    public string $email = '';

    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => UserStatus::ACTIVE->value],
                'message' => Yii::t('auth', 'error.user_not_found')
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => Yii::t('auth', 'field.email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was sent
     * @throws Exception
     */
    public function sendEmail(): bool
    {
        /** @var User $user */
        $user = User::findOne([
            'status' => UserStatus::ACTIVE->value,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!$user->password_reset_token || !User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $emailService = new EmailService();
        return $emailService->sendPasswordResetEmail($user, $user->password_reset_token);
    }
}
