<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

use app\modules\auth\models\LoginForm;

/* @var $this yii\web\View */
/* @var $model LoginForm */

$this->title = Yii::t('auth', 'title.login');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('auth', 'message.login_instructions') ?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
            ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <div class="mb-3">
                        <?= Html::submitButton(Yii::t('auth', 'button.login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        <?= Html::a(Yii::t('auth', 'button.signup'), ['signup'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                    <div>
                        <?= Html::a(
                            Yii::t('auth', 'link.forgot_password'),
                            ['/forgot-password'],
                            ['class' => 'text-decoration-none']
                        ) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
