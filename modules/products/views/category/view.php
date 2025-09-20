<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\products\models\ProductCategory;

/* @var $this yii\web\View */
/* @var $model ProductCategory */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('products.category', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('product.category.update')): ?>
            <?= Html::a(Yii::t('app', 'button.update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('product.category.delete')): ?>
            <?= Html::a(Yii::t('app', 'button.delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'message.confirm_delete'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
