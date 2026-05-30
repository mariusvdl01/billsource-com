<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $email string */
/* @var $authKey string */
/* @var $firstname string */
/* @var $lastname string */

$hostInfo = Yii::$app->params['hostInfo'];

if (method_exists(Yii::$app->request, 'getHostInfo')) {
    $hostInfo = Yii::$app->request->getHostInfo();
}

?>
    Dear <?= Html::encode($firstname . ' ' . $lastname) ?>,

    Good day <?= Html::encode($firstname . ' ' . $lastname) ?>, 
    Billsource has a new task: <?=$refno?>, for you.
    Register or login at <?= $hostInfo ?> to view or pay your bills.
    Contact <?=Yii::$app->params['adminEmail']?> or <?=Yii::$app->params['contactSales']?> for more information.