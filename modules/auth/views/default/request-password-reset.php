<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

use app\modules\auth\models\ResetPasswordForm;

/* @var $this yii\web\View */
/* @var $model ResetPasswordForm */

$this->title = Yii::t('auth', 'title.request_password_reset');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('auth', 'message.password_reset_instructions') ?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
                'options' => ['autocomplete' => 'off']
            ]); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <div class="mb-3">
                        <?= Html::submitButton(Yii::t('auth', 'button.send_reset_link'), ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
                    </div>
                    <div>
                        <?= Html::a(
                            Yii::t('auth', 'link.back_to_login'),
                            ['/login'],
                            ['class' => 'text-decoration-none']
                        ) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
