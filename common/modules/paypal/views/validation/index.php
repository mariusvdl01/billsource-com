<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

$this->title = 'Payment response';
?>

<div class="paypal-validation-index">
	<div class="col-sm-12">
		<h4>Payment result</h4>
		<?php if($success == 'false' || $authorize) : ?>
			<div class="alert alert-warning" role="alert">
				<p class="text-danger">
					<?= Html::encode('User cancelled the approval'); ?>
				</p>
			</div>
  		<?= Html::a(Yii::t('app', 'Retry'), '/payment', [
				'class' => 'btn btn-default'
			]) ?>
		<?php else : ?>
			<?php if($approved) : ?>
				<div class="alert alert-success" role="alert">
					<p>
						<?= Html::encode('Sweet!!! Payment was successfully approved'); ?>
					</p>
				</div>
				<?= Html::a(Yii::t('app', 'Pay Another Bill'),
					Yii::$app->user->identity->business_user ? '/business/creditor/unpaid': '/individual/bill/unpaid',
					[ 'class' => 'btn btn-default']
				)?>
			<?php else : ?>
				<div class="alert alert-warning" role="alert">
					<p class="text-danger">
						<?= Html::encode('Oops!!! Payment failed to be approved'); ?>
					</p>
				</div>
				<?= Html::a(Yii::t('app', 'Home'),
					Yii::$app->user->identity->business_user ? '/business/profile': '/individual/profile',
					[ 'class' => 'btn btn-default']
				)?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>