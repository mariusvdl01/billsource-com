<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode('Reset Password'), $resetLink) ?></p>
</div>
