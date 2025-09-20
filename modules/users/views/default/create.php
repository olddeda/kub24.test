<?php

use yii\helpers\Html;

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $model User */

$this->title = Yii::t('users', 'title.create');

$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
