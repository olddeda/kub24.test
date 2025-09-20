<?php

use app\modules\products\models\Product;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Product */

$this->title = Yii::t('products', 'title.create');

$this->params['breadcrumbs'][] = ['label' => Yii::t('products', 'title.index'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
