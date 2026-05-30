<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\QuoteAsset;

$this->title = 'New Quote';
QuoteAsset::register($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="quote-create">
			<h3 class="text-center"><?= Html::encode($this->title) ?></h3>
			<?= $this->render('_form',[
				'customers'		=> $customers,
				'biller' 		=> $biller,
		        'quote' 		=> $quote,
		    	'lineManager'	=> $lineManager,
				'products'		=> $products,
                'terms'			=> $terms,
		    ]) ?>
		</div>
	</div>
</div>