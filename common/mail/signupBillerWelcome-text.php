<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
?>
    Dear <?= Html::encode($form->trading_name) ?>,

    Thank you for registering on the billsource system.

    Manage your outstanding bill issuing and payments conveniently and securely

<?= Yii::$app->request->getServerName() ?> offers an alternative to manage outstanding bills
<?= Yii::$app->request->getServerName() ?> offers an aggregated view of all outstanding bills
<?= Yii::$app->request->getServerName() ?> offers a convenient way of settling outstanding bills
<?= Yii::$app->request->getServerName() ?> also offers benefit of access to a paperless environment

Login to <?= Html::a(Yii::t('app', 'Billsource'), ['account/login']) ?> to make your money move faster.
Email: <?= $user->email?>
Password: <?= $form->getPassword() ?>