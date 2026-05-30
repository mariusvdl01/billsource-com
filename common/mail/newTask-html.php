<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $email string */
/* @var $authKey string */
/* @var $firstname string */
/* @var $lastname string */

$serverName = Yii::$app->params['serverName'];
$hostInfo = Yii::$app->params['hostInfo'];

if (method_exists(Yii::$app->request, 'getHostInfo')) {
    $hostInfo = Yii::$app->request->getHostInfo();
}

if (property_exists(Yii::$app->request, 'serverName')) {
    $serverName = Yii::$app->request->serverName;
}
?>
<div class="signup-welcome">
    <p>Dear <?= Html::encode($firstname) ?>,</p>

    <p>Good day <?= Html::encode($firstname . ' ' . $lastname) ?>,
        Billsource has a new task: <?=$refno?>, for you.<br><br>
        Register or login at <a href="<?= $hostInfo ?>"><?= $serverName; ?></a> to view or pay your bills.<br><br>
        Contact <?=Yii::$app->params['adminEmail']?> or <?=Yii::$app->params['contactSales']?> for more information.<br><br>
    </p>
</div>