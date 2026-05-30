<?php

/* @var $this yii\web\View */
/* @var $customers common\models\business\BusinessClientCrm */
/* @var $biller common\models\business\BusinessClient */
/* @var $ticket common\models\invoice\Ticket */
/* @var $lineManager common\models\invoice\InvoiceLineManager */
/* @var $products common\models\catalog\Product */
/* @var $terms array */

use yii\helpers\Html;

$this->title = Yii::t('app', 'New Ticket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\frontend\assets\TicketAsset::register($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="ticket-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'customers'		=> $customers,
                'statuses'      => $statuses,
                'biller' 		=> $biller,
                'ticket' 		=> $ticket,
                'lineManager'	=> $lineManager,
                'products'		=> $products,
                'terms'			=> $terms,
            ]) ?>

        </div>
    </div>
</div>