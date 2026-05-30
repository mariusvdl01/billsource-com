<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $employees common\models\business\BusinessEmployee */
/* @var $biller common\models\business\BusinessClient */
/* @var $ticket common\models\invoice\Ticket */
/* @var $lineManager common\models\invoice\InvoiceLineManager */
/* @var $products common\models\catalog\Product */
/* @var $terms array */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Ticket',
]) . $ticket->reference_number;
$this->params['breadcrumbs'][] = ['label' => $ticket->reference_number, 'url' => ['view', 'id' => $ticket->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
\frontend\assets\PayslipAsset::register($this);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="ticket-update">
            <h3><?= Html::encode($this->title) ?></h3>
            <?= $this->render('_form',[
                'customer'		=> $customers,
                'biller' 		=> $biller,
                'ticket' 		=> $ticket,
                'lineManager'	=> $lineManager,
                'statuses'		=> $statuses,
                'products'		=> $products,
                'terms'			=> $terms,
            ]) ?>

        </div>
    </div>
</div>