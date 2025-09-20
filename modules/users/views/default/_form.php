<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\users\models\User;
use app\modules\users\enums\UserStatus;

/* @var $this yii\web\View */
/* @var $model User */

$isUpdate = !$model->isNewRecord;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off']
    ]); ?>

    <fieldset class="border rounded-3 p-3">
        <legend class="float-none w-auto px-3"><?= Yii::t('users', 'header.general') ?></legend>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput([
                'maxlength' => true,
                'autocomplete' => 'off',
                'autocorrect' => 'off',
                'autocapitalize' => 'off',
                'spellcheck' => 'false'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'autocomplete' => 'off',
                'autocorrect' => 'off',
                'autocapitalize' => 'off',
                'spellcheck' => 'false'
            ]) ?>
        </div>
    </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList([
                    UserStatus::ACTIVE->value => UserStatus::ACTIVE->label(),
                    UserStatus::INACTIVE->value => UserStatus::INACTIVE->label(),
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'role')->dropDownList(
                    User::getRolesList(),
                    [
                        'prompt' => Yii::t('users', 'field.select_role')
                    ]
                )->label(Yii::t('users', 'field.role')) ?>
            </div>
        </div>
    </fieldset>

    <?php if (!$isUpdate): ?>
    <fieldset class="border rounded-3 mt-4 p-3">
        <legend class="float-none w-auto px-3"><?= Yii::t('users', 'header.password') ?></legend>

        <div class="mb-3">
            <label class="form-label" for="password-input"><?= Yii::t('users', 'field.password') ?></label>
            <div class="input-group">
                <?= Html::activePasswordInput($model, 'password_hash', [
                    'class' => 'form-control',
                    'maxlength' => true,
                    'id' => 'password-input',
                    'autocomplete' => 'new-password',
                    'autocorrect' => 'off',
                    'autocapitalize' => 'off',
                    'spellcheck' => 'false'
                ]) ?>
                <?= Html::button('<i class="bi bi-eye"></i>', [
                    'class' => 'btn btn-outline-secondary',
                    'type' => 'button',
                    'id' => 'toggle-password-btn',
                    'title' => Yii::t('users', 'button.toggle_password')
                ]) ?>
            </div>
            <?php if ($model->hasErrors('password_hash')): ?>
                <div class="invalid-feedback d-block">
                    <?= Html::encode($model->getFirstError('password_hash')) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mb-3">
            <?= Html::button('<i class="bi bi-arrow-clockwise"></i> ' . Yii::t('users', 'button.generate_password'), [
                'class' => 'btn btn-outline-primary',
                'id' => 'generate-password-btn'
            ]) ?>
        </div>
    </fieldset>
    <?php endif; ?>

    <div class="form-group mt-4">
        <?= Html::submitButton(Yii::t('app', 'button.save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'button.cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
