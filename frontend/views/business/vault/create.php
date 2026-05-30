<?php

use frontend\assets\InvoiceAsset;

/* @var $this yii\web\View */

$this->title = 'Tax Invoice';
InvoiceAsset::register($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="vault-invoice-create">
			<h3>Vault Tax Invoice</h3>
			<p><font color="#CC0000">*</font> Required field</p>
			<?= $this->render('_form',[
				'biller' 		=> $biller,
		        'invoice' 		=> $invoice,
		    ]) ?>
		</div>
	</div>
</div>