<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\products\models\Product;

/* @var $this yii\web\View */
/* @var $model Product */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('product.update')): ?>
        <?= Html::a(Yii::t('products', 'button.update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('product.delete')): ?>
        <?= Html::a(Yii::t('app', 'button.delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('products', 'message.confirm_delete'),
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
                'attribute' => 'price',
                'value' => $model->getFormattedPrice(),
            ],
            [
                'attribute' => 'category_id',
                'value' => $model->getCategoryName(),
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
