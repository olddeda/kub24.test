<?php

use yii\helpers\Html;

use app\modules\products\models\ProductCategory;

/* @var $this yii\web\View */
/* @var $model ProductCategory */

$this->title = Yii::t('products.category', 'title.create');

$this->params['breadcrumbs'][] = ['label' => Yii::t('products.category', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
