<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

use app\modules\auth\models\PasswordResetRequestForm;

/* @var $this yii\web\View */
/* @var $model PasswordResetRequestForm */

$this->title = Yii::t('auth', 'title.reset_password');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('auth', 'message.choose_new_password') ?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'reset-password-form',
                'options' => ['autocomplete' => 'off']
            ]); ?>

                <div class="mb-3">
                    <label class="form-label" for="password-input"><?= Yii::t('auth', 'field.new_password') ?></label>
                    <div class="input-group">
                        <?= Html::activePasswordInput($model, 'password', [
                            'class' => 'form-control',
                            'id' => 'password-input',
                            'autofocus' => true,
                            'autocomplete' => 'new-password'
                        ]) ?>
                        <?= Html::button('<i class="bi bi-eye"></i>', [
                            'class' => 'btn btn-outline-secondary',
                            'type' => 'button',
                            'id' => 'toggle-password-btn',
                            'title' => Yii::t('users', 'button.toggle_password')
                        ]) ?>
                    </div>
                    <?php if ($model->hasErrors('password')): ?>
                        <div class="invalid-feedback d-block">
                            <?= Html::encode($model->getFirstError('password')) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <?= Html::button('<i class="bi bi-arrow-clockwise"></i> ' . Yii::t('users', 'button.generate_password'), [
                        'class' => 'btn btn-outline-primary',
                        'id' => 'generate-password-btn'
                    ]) ?>
                </div>

                <div class="form-group">
                    <div>
                        <?= Html::submitButton(Yii::t('auth', 'button.save_password'), ['class' => 'btn btn-success', 'name' => 'save-button']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
