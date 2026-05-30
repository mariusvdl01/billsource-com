<?php
/* @var $this yii\web\View */

use frontend\assets\InvoiceAsset;

$this->title = Yii::t('app', 'Edit {modelClass}: ', [
    'modelClass' => 'Invoice',
]) . ' ' . $invoice->reference_number;
InvoiceAsset::register($this);
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="invoice-edit">
			
			<?= $this->render('_form',[
				'customers'		=> $customers,
				'biller' 		=> $biller,
		        'invoice' 		=> $invoice,
		    	'lineManager'	=> $lineManager,
				'statuses'		=> $statuses,
				'products'		=> $products,
				'terms'			=> $terms,
		    ]) ?>
		</div>
	</div>
</div>
