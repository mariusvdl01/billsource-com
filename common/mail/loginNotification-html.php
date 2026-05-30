<?php

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
?>
<div class="login-notification">

    <p><strong>Dear User,</strong><br><br>
        You or someone else with your login credentials logged into your
        <a href="<?= Yii::$app->request->getHostInfo() ?>"><?= Yii::$app->request->serverName; ?></a> account.<br><br>
    </p>
</div>