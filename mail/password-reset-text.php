<?php

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $user User */
/* @var $resetUrl string */
?>
<?= Yii::t('email', 'password_reset.title') ?>

<?= Yii::t('email', 'password_reset.greeting', ['username' => $user->username]) ?>

<?= Yii::t('email', 'password_reset.message') ?>

<?= Yii::t('email', 'password_reset.reset_link') ?>: <?= $resetUrl ?>

<?= Yii::t('email', 'password_reset.ignore_info') ?>
