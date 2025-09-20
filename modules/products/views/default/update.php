<?php

use yii\helpers\Html;

use app\modules\products\models\Product;

/* @var $this yii\web\View */
/* @var $model Product */

$this->title = Yii::t('products', 'title.update') . ': ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'button.update');
?>
<div class="product-admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
