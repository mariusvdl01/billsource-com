<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\InvoiceAsset;

$this->title = 'New Cash Invoice';
InvoiceAsset::register($this);
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="cash-invoice-create">
			<h3 class="text-center"><?= Html::encode($this->title) ?></h3>
			<?= $this->render('_form',[
				//'customers'		=> $customers,
				'biller' 		=> $biller,
		        'invoice' 		=> $invoice,
		    	'lineManager'	=> $lineManager,
				'products'		=> $products
		    ]) ?>
		</div>
	</div>
</div>