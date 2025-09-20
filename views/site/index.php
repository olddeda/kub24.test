<?php

use yii\helpers\Html;
use app\modules\users\services\UserService;
use app\modules\products\services\ProductService;
use app\modules\products\services\CategoryService;

$this->title = Yii::t('app', 'title.dashboard');
$this->params['breadcrumbs'][] = $this->title;

$totalUsers = UserService::getTotalCount();
$totalProducts = ProductService::getTotalCount();
$totalCategories = CategoryService::getTotalCount();
?>

<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title"><?= Yii::t('products', 'title') ?></h2>
                        <p class="card-text display-6 text-primary"><?= $totalProducts ?></p>
                        <p class="card-text"><?= Yii::t('products', 'count.total') ?></p>
                        <?php if (Yii::$app->user->can('product.index')): ?>
                            <?= Html::a(Yii::t('app', 'button.view'), ['/products/index'], ['class' => 'btn btn-outline-primary']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title"><?= Yii::t('products.category', 'title') ?></h2>
                        <p class="card-text display-6 text-success"><?= $totalCategories ?></p>
                        <p class="card-text"><?= Yii::t('products.category', 'count.total') ?></p>
                        <?php if (Yii::$app->user->can('product.category.index')): ?>
                            <?= Html::a(Yii::t('app', 'button.view'), ['/categories/index'], ['class' => 'btn btn-outline-success']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if (Yii::$app->user->can('user.index')): ?>
            <div class="col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="card-title"><?= Yii::t('users', 'title') ?></h2>
                        <p class="card-text display-6 text-warning"><?= $totalUsers ?></p>
                        <p class="card-text"><?= Yii::t('users', 'count.total') ?></p>
                        <?php if (Yii::$app->user->can('user.index')): ?>
                            <?= Html::a(Yii::t('app', 'button.manage'), ['/users/index'], ['class' => 'btn btn-outline-warning']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
