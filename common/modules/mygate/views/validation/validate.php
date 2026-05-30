<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

$this->title = 'Payment response';
?>

<div class="mygate-validation-index">
	<div class="col-sm-12">
		<h4>Payment result summary</h4>
		
		<?php if(!$valid) : ?>
			<?= DetailView::widget([
     			'model' => $post,
      			'attributes' => [
          			'_ERROR_CODE',               
          			'_ERROR_MESSAGE',   
          			'_ERROR_DETAIL'
      			],
  			]) ?>
  		<?= Html::a(Yii::t('app', 'Retry'), '/payment', [ 'class' => 'btn btn-default'])?>
		<?php else : ?>
			<?= DetailView::widget([
     			'model' => $post,
      			'attributes' => [
          			'_CURRENCYCODE',               
          			'_TRANSACTIONINDEX',    
          			'_AMOUNT',
      				'TXTACQUIRERDATETIME',
      				'_CARDCOUNTRY',
      		],
			]) ?>
			<?= Html::a(Yii::t('app', 'Pay Another Bill'), 
				Yii::$app->user->identity->business_user ? '/business/invoice/creditor': '/individual/bill/unpaid', 
				[ 'class' => 'btn btn-default']
			)?>
			<?= Html::a(Yii::t('app', 'Home'), 
				Yii::$app->user->identity->business_user ? '/business/profile': '/individual/profile', 
				[ 'class' => 'btn btn-default']
			)?>
		<?php endif; ?>
	</div>
</div>