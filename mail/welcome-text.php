<?php

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $user User */
?>
<?= Yii::t('email', 'welcome.title') ?>

<?= Yii::t('email', 'welcome.greeting', ['username' => $user->username]) ?>

<?= Yii::t('email', 'welcome.message') ?>

<?= Yii::t('email', 'welcome.login_url') ?>: <?= Yii::$app->urlManager->createAbsoluteUrl(['auth/default/login']) ?>

<?= Yii::t('email', 'welcome.support_info') ?>
