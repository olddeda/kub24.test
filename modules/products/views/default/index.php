<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('products', 'title.index');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-default-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('product.create')): ?>
    <p>
        <?= Html::a(Yii::t('products', 'button.create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description:ntext',
            [
                'attribute' => 'price',
                'value' => function ($model) {
                    return $model->getFormattedPrice();
                },
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->getCategoryName();
                },
                'label' => Yii::t('products', 'field.category'),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
            ],

            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['width' => '80'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('product.view'),
                    'update' => Yii::$app->user->can('product.update'),
                    'delete' => Yii::$app->user->can('product.delete'),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

    <?php if (Yii::$app->user->can('product.create')): ?>
    <p>
        <?= Html::a(Yii::t('products', 'button.create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

</div>
