<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

use app\modules\products\models\ProductCategory;
use app\modules\products\enums\CategoryStatus;

/* @var $this yii\web\View */
/* @var $model ProductCategory */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(CategoryStatus::labels(), ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'button.save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'button.cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
