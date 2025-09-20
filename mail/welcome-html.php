<?php

use yii\helpers\Html;
use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $user User */
?>

<h2><?= Yii::t('email', 'welcome.title') ?></h2>

<p><?= Yii::t('email', 'welcome.greeting', ['username' => Html::encode($user->username)]) ?></p>

<p><?= Yii::t('email', 'welcome.message') ?></p>

<div style="text-align: center; margin: 30px 0;">
    <?= Html::a(
        Yii::t('email', 'welcome.login_button'),
        Yii::$app->urlManager->createAbsoluteUrl(['auth/default/login']),
        ['class' => 'button']
    ) ?>
</div>

<p><?= Yii::t('email', 'welcome.support_info') ?></p>
