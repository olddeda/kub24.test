<?php

use yii\helpers\Html;
use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $user User */
/* @var $resetUrl string */
?>

<h2><?= Yii::t('email', 'password_reset.title') ?></h2>

<p><?= Yii::t('email', 'password_reset.greeting', ['username' => Html::encode($user->username)]) ?></p>

<p><?= Yii::t('email', 'password_reset.message') ?></p>

<div style="text-align: center; margin: 30px 0;">
    <?= Html::a(
        Yii::t('email', 'password_reset.reset_button'),
        $resetUrl,
        ['class' => 'button']
    ) ?>
</div>

<p style="font-size: 12px; color: #666;">
    <?= Yii::t('email', 'password_reset.link_info') ?><br>
    <?= Html::encode($resetUrl) ?>
</p>

<p style="font-size: 12px; color: #666;">
    <?= Yii::t('email', 'password_reset.ignore_info') ?>
</p>
