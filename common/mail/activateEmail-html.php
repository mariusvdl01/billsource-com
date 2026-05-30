<?php

/* @var $this yii\web\View */
/* @var $user frontend\models\User */

$loginLink = Yii::$app->urlManager->createAbsoluteUrl('account/login');
?>
<div class="email-activate">

    <p>Thank you for verifyinh your email.</p>

    <p>Your thank you gift has been activated.</p>

    <p>You can <a href="<?= $loginLink ?>">Login</a> to discover your gift.</p>
</div>