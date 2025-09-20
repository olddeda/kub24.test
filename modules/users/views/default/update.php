<?php

use yii\helpers\Html;

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $model User */

$this->title = Yii::t('users', 'title.update') . ': ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'button.update');
?>
<div class="user-admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= $this->render('_password_form', [
        'model' => $model,
    ]) ?>

</div>
