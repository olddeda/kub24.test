<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= Yii::$app->name ?>
<?= str_repeat('=', mb_strlen(Yii::$app->name, Yii::$app->charset)) ?>

<?= $content ?>

--
© <?= date('Y') ?> <?= Yii::$app->name ?>. <?= Yii::t('email', 'footer.all_rights_reserved') ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>
