<?php

use yii\helpers\Html;

use app\modules\products\models\ProductCategory;

/* @var $this yii\web\View */
/* @var $model ProductCategory */

$this->title = Yii::t('products.category', 'title.update') . ': ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('products.category', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('products.category', 'title.update');
?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
