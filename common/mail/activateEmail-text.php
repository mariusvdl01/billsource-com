<?php

/* @var $this yii\web\View */
/* @var $user frontend\models\User */

$loginLink = Yii::$app->urlManager->createAbsoluteUrl('account/login');
?>

Thank you for activating your email.

Your thank you gift has been activated.

You can <a href="<?= $loginLink ?>">Login</a> to discover your reward points.
