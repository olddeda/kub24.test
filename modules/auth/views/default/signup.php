<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

use app\modules\auth\models\SignupForm;

/* @var $this yii\web\View */
/* @var $model SignupForm */

$this->title = Yii::t('auth', 'title.signup');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('auth', 'message.signup_instructions') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'password_repeat')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('auth', 'button.signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?= Html::a(Yii::t('auth', 'button.login'), ['login'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
