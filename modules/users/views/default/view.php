<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $model User */

$this->title = $model->username;

$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('user.update')): ?>
            <?= Html::a(Yii::t('users', 'button.update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        
        <?php if (Yii::$app->user->can('user.delete') && $model->id !== Yii::$app->user->id): ?>
            <?= Html::a(Yii::t('app', 'button.delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('users', 'message.confirm_delete'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
            ],
            [
                'attribute' => 'role',
                'label' => Yii::t('users', 'field.role'),
                'value' => function ($model) {
                    $roles = $model->getRoles();
                    return !empty($roles) ? array_key_first($roles) : Yii::t('app', 'label.not_set');
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
