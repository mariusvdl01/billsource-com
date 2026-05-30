<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Billsource - Login';

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="site-login">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

            <p class="text-center">Please fill out the following fields to login</p>
        	<br />
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-sm-9\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
            ]); ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <!--<?= $form->field($model, 'rememberMe', [
                'template' => "<div class=\"col-sm-offset-1 col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
            ])->checkbox() ?>-->
            <div class="form-group">
                <div class="col-xs-offset-2 col-sm-offset-2 col-md-offset-2">
                    <?= Html::a(Yii::t('app', 'Forgot Password?'), ['account/request-password-reset']) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button-main']) ?>
                    <?= Html::a(Yii::t('app', 'Sign up'), ['/account/signup', 'tab' => '1'], ['class' => 'btn btn-default', 'name' => 'login-button-main']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
