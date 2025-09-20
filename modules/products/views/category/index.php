<?php

use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('products.category', 'title.index');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('product.category.create')): ?>
    <p>
        <?= Html::a(Yii::t('products.category', 'button.create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
            ],
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['width' => '80'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('product.category.view'),
                    'update' => Yii::$app->user->can('product.category.update'),
                    'delete' => Yii::$app->user->can('product.category.delete'),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

    <?php if (Yii::$app->user->can('product.category.create')): ?>
    <p>
        <?= Html::a(Yii::t('products.category', 'button.create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

</div>
