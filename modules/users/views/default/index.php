<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('users', 'title.index');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('user.create')): ?>
    <p>
        <?= Html::a(Yii::t('users', 'button.create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
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

            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['width' => '80'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('user.view'),
                    'update' => Yii::$app->user->can('user.update'),
                    'delete' => function ($model) {
                        return Yii::$app->user->can('user.delete') && $model->id !== Yii::$app->user->id;
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

    <?php if (Yii::$app->user->can('user.create')): ?>
    <p>
        <?= Html::a(Yii::t('users', 'button.create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

</div>