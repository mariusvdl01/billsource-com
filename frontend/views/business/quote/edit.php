<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\QuoteAsset;

$this->title = 'Edit Quote';
QuoteAsset::register($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="quote-edit">
			
			<?= $this->render('_form',[
				'customers'		=> $customers,
				'biller' 		=> $biller,
		        'quote' 		=> $quote,
		    	'lineManager'	=> $lineManager,
				'statuses'		=> $statuses,
				'products'		=> $products,
                'terms'			=> $terms,
		    ]) ?>
		</div>
	</div>
</div>