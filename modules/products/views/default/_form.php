<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\products\models\Product;
use app\modules\products\models\ProductCategory;
use app\modules\products\enums\ProductStatus;

/* @var $this yii\web\View */
/* @var $model Product */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0']) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ProductCategory::getCategoryList(),
        ['prompt' => Yii::t('products', 'field.select_category')]
    ) ?>

    <?= $form->field($model, 'status')->dropDownList([
        ProductStatus::ACTIVE->value => ProductStatus::ACTIVE->label(),
        ProductStatus::INACTIVE->value => ProductStatus::INACTIVE->label(),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'button.save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'button.cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
