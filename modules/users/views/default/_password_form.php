<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\User */
?>

<div class="user-password-form mt-4">
    <fieldset class="border rounded-3 p-3">
        <legend class="float-none w-auto px-3"><?= Yii::t('users', 'header.change_password') ?></legend>

        <?php $form = ActiveForm::begin([
            'id' => 'password-form',
            'action' => ['change-password', 'id' => $model->id],
            'options' => ['autocomplete' => 'off']
        ]); ?>

        <div class="mb-3">
            <label class="form-label" for="new-password-input"><?= Yii::t('users', 'field.new_password') ?></label>
            <div class="input-group">
                <?= Html::passwordInput('User[new_password]', '', [
                    'class' => 'form-control',
                    'maxlength' => true,
                    'id' => 'new-password-input',
                    'autocomplete' => 'new-password',
                    'autocorrect' => 'off',
                    'autocapitalize' => 'off',
                    'spellcheck' => 'false'
                ]) ?>
                <?= Html::button('<i class="bi bi-eye"></i>', [
                    'class' => 'btn btn-outline-secondary',
                    'type' => 'button',
                    'id' => 'toggle-new-password-btn',
                    'title' => Yii::t('users', 'button.toggle_password')
                ]) ?>
            </div>
        </div>

        <div class="mb-3">
            <?= Html::button('<i class="bi bi-arrow-clockwise"></i> ' . Yii::t('users', 'button.generate_password'), [
                'class' => 'btn btn-outline-primary',
                'id' => 'generate-new-password-btn'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </fieldset>

    <div class="form-group mt-4">
        <?= Html::submitButton(Yii::t('users', 'button.change_password'), [
            'class' => 'btn btn-success',
            'data-confirm' => Yii::t('users', 'message.confirm_password_change')
        ]) ?>
    </div>
</div>
