<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
?>
<div class="signup-welcome">
    <p>Dear <?= Html::encode($form->trading_name) ?>,</p>

    <p>Thank you for registering on the billsource system.</p>

    <font face="Arial">Manage your outstanding bill issuing and payments conveniently and securely</font>

    <ul>
        <li><font size="2" face="Arial"><?= Yii::$app->request->getServerName() ?>&nbsp; offers an alternative
                to manage outstanding bills</font></li>
        <li><font size="2" face="Arial"><?= Yii::$app->request->getServerName() ?> offers an aggregated view
                of all outstanding bills</font></li>
        <li><font size="2" face="Arial"><?= Yii::$app->request->getServerName() ?> offers a convenient way of
                settling outstanding bills</font></li>

        <li><font size="2" face="Arial"><?= Yii::$app->request->getServerName() ?> also offers benefit of
                access to a paperless environment</font></li>
    </ul>
    
    <p>Login to <?= Html::a(Yii::t('app', 'Billsource'), ['account/login']) ?> to make your money move faster.</p>
    <p><strong>Email: </strong><?= $user->email?></p>
    <p><strong>Password: </strong><?= $form->getPassword() ?></p>
</div>