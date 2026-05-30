<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user frontend\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([
    'account/activate',
    'email' => $signupForm->email,
    'token' => $auth_key,

]);
?>
    Dear <?= Html::encode($signupForm->firstname . ' ' . $signupForm->lastname) ?>,

    Thank you for registering on the billsource system.

    Manage your outstanding bill issuing and payments conveniently and securely

<?= Yii::$app->request->getServerName() ?> offers an alternative to manage outstanding bills
<?= Yii::$app->request->getServerName() ?> offers an aggregated view of all outstanding bills
<?= Yii::$app->request->getServerName() ?> offers a convenient way of settling outstanding bills
<?= Yii::$app->request->getServerName() ?> also offers benefit of access to a paperless environment
    
To activate the registration click <?= Html::a(Html::encode('Activate account'), $resetLink) ?>