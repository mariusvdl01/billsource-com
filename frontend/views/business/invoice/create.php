<?php


/* @var $this yii\web\View */
/* @var $employees common\models\business\BusinessClientCrm */
/* @var $biller common\models\business\BusinessClient */
/* @var $payslip common\models\invoice\Invoice */
/* @var $lineManager common\models\invoice\InvoiceLineManager */
/* @var $products common\models\catalog\Product */
/* @var $terms array */

use yii\helpers\Html;
use frontend\assets\InvoiceAsset;

$this->title = 'New Tax Invoice';
InvoiceAsset::register($this);
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="invoice-create">
			<h3 class="text-center"><?= Html::encode($this->title) ?></h3>
			<?= $this->render('_form',[
				'customers'		=> $customers,
				'biller' 		=> $biller,
		        'invoice' 		=> $invoice,
		    	'lineManager'	=> $lineManager,
				'products'		=> $products,
				'terms'			=> $terms,
		    ]) ?>
		</div>
	</div>
</div>