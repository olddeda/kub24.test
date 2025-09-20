<?php

declare(strict_types=1);

namespace app\components\services;

use Yii;
use yii\base\Exception;
use yii\mail\MailerInterface;
use app\modules\users\models\User;

class EmailService
{
    private MailerInterface $mailer;
    
    public function __construct()
    {
        $this->mailer = Yii::$app->mailer;
    }
    
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            $message = $this->mailer->compose(
                ['html' => 'welcome-html', 'text' => 'welcome-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($user->email)
            ->setSubject(Yii::t('email', 'subject.welcome', ['appName' => Yii::$app->name]));
            
            return $message->send();
        } catch (Exception $e) {
            Yii::error("Failed to send welcome email to {$user->email}: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(User $user, string $token): bool
    {
        try {
            $resetUrl = Yii::$app->urlManager->createAbsoluteUrl(['auth/default/reset-password', 'token' => $token]);
            
            $message = $this->mailer->compose(
                ['html' => 'password-reset-html', 'text' => 'password-reset-text'],
                ['user' => $user, 'resetUrl' => $resetUrl]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($user->email)
            ->setSubject(Yii::t('email', 'subject.password_reset', ['appName' => Yii::$app->name]));
            
            return $message->send();
        } catch (Exception $e) {
            Yii::error("Failed to send password reset email to {$user->email}: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
    
    /**
     * Send notification email to admin
     */
    public function sendAdminNotification(string $subject, string $message): bool
    {
        try {
            $email = $this->mailer->compose()
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject($subject)
                ->setTextBody($message);
            
            return $email->send();
        } catch (Exception $e) {
            Yii::error("Failed to send admin notification: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
