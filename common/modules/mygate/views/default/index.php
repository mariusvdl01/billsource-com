<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\business\BusinessClient as Client;

$this->title = 'Make payment';
$user = Yii::$app->user->identity;
$client = Yii::$app->params['client'];
$collector = isset($client) ? ($client->type == Client::CATEGORY_COLLECTOR) : false;
?>

<div class="mygate-default-index">
	<div class="col-sm-12">
		<h3>Payment summary</h3>
		<?php $form = ActiveForm::begin([
			'id' => 'mygate-form',
			'action' => Url::to($rpp),
		]) ?>
			<table class="table table-hover">
				<input type="hidden" name="Mode" value="0">
				<input type="hidden" name="txtMerchantID" value="24EBA756-8638-4428-831A-CC33E7BBFCFA">
				<input type="hidden" name="txtApplicationID" value="E05D4732-208D-4DF8-B61E-A9813B7428CA">
				<input type="hidden" name="txtMerchantReference" value="<?= 'BSI'.$index; ?>">
				<input type="hidden" name="txtCurrencyCode" value="ZAR">
				<input type="hidden" name="txtRedirectSuccessfulURL" value="<?= $returnUrl; ?>">
				<input type="hidden" name="txtRedirectFailedURL" value="<?= $returnUrl; ?>">
				<input type="hidden" name="Variable1" id="Variable1" value="<?= $index; ?>" />
				<tr>
					<th><strong>Reference</strong></th>
					<th><strong>Description</strong></th>
					<th style="text-align: right"><strong>Amount</strong></th>
					<?php if($collector) : ?>
						<th style="text-align: right"><strong>Amount to pay (85%)</strong></th>
					<?php endif; ?>
				</tr>
				<tbody>
				<?php 
					$subtotal = 0.0;
					$total = 0.0;
					$i = 0;
					$collectorTotal = 0.0;
				?>
					<?php foreach($pay_array as $model) : ?>
						<?php $i++ ?>
						<tr>
							<td>
								<input type="hidden" name="txtQty<?= $i ?>" id="txtQty<?= $i ?>" value="1" /> 
	            				<input type="hidden" name="txtItemRef<?= $i ?>" id="txtItemRef<?= $i ?>" value="<?= $model['invoice_id'] ?>" />
	            				<?= $model['reference_number'] ?>
							</td>
							<td>
								<input type="hidden" name="txtItemDescr<?= $i ?>" id="txtItemDescr<?= $i ?>" 
								value="<?= $model['trading_name'] . ' - ' . $model['reference_number'] ?>" />
								<?= $model['trading_name'] . ' - ' . $model['reference_number'] ?>
							</td>
							<td style="text-align: right">
								<input type="hidden" name="txtItemAmount<?= $i ?>" id="txtItemAmount<?= $i ?>" value="<?= number_format($model['total'], 2) ?>" />
								<?= number_format($model['total'], 2) ?>
							</td>
							<?php if($collector) : ?>
								<td style="text-align: right">
									<input type="hidden" name="txtItemAmount<?= $i ?>" id="txtItemAmount<?= $i ?>" value="<?= number_format($model['total'], 2) ?>" />
									<?= number_format($model['total'] * .85, 2) ?>
								</td>
							<?php endif; ?>
						</tr>
						<?php
							$subtotal = ($subtotal + $model['total']);
							$collectorTotal += ($model['total'] * .85);
						?>
					<?php endforeach; ?>
					<?php foreach($data as $fee) : ?>
						<tr>
							<?php $i++ ?>
							<td>
								<input type="hidden" name="txtQty<?= $i ?>" id="txtQty<?= $i ?>" value="1" /> 
		            			<input type="hidden" name="txtItemRef<?= $i ?>" id="txtItemRef<?= $i ?>" value="<?= $fee['reference_number'] ?>" />
								<?= $fee['reference_number'] ?>
							</td>
							<td>
								<input type="hidden" name="txtItemDescr<?= $i ?>" id="txtItemDescr<?= $i ?>" value="<?= $fee['alt_business_name'] ?>" />
								<?= $fee['alt_business_name'] ?>
							</td>
							<td style="text-align: right">
								<input type="hidden" name="txtItemAmount<?= $i ?>" id="txtItemAmount<?= $i ?>" value="<?= number_format($fee['amount'], 2) ?>" />
								<?= number_format($fee['amount'], 2) ?>
							</td>
							<?php if($collector) : ?>
								<td style="text-align: right">
									<input type="hidden" name="txtItemAmount<?= $i ?>" id="txtItemAmount<?= $i ?>" value="<?= number_format($fee['amount'], 2) ?>" />
									<?= number_format($fee['amount'], 2) ?>
								</td>
							<?php endif; ?>
						</tr>
						<?php
							$subtotal = ($subtotal + $fee['amount']);
							$collectorTotal += ($fee['amount']);
						?>
					<?php endforeach; ?>
					<tr>
						<?php $total = $subtotal; ?>
						<td colspan="2"><strong>Total</strong></td>
						<td style="text-align: right"><?='R' . number_format($total, 2, ',', ' ') ?></td>
						<?php if($collector) : ?>
							<td style="text-align: right"><?='R' . number_format($collectorTotal, 2, ',', ' ') ?></td>
							<input type="hidden" name="txtPrice" id="txtPrice" value="<?= round($collectorTotal, 2)
							?>" />
						<?php else : ?>
							<input type="hidden" name="txtPrice" id="txtPrice" value="<?= $total ?>" />
						<?php endif; ?>
					</tr>
				</tbody>
			</table>
			<div class="col-sm-offset-9 form-group">
				<?= Html::submitButton(Yii::t('app', 'Pay'), ['class' => 'btn btn-primary']) ?>
				<?= Html::a(Yii::t('app', 'Cancel'),
					$user->business_user ? $collector ?
						'/business/collector' : '/business/creditor/unpaid' :
						'/individual/bill/unpaid',
					['class' => 'btn btn-default']) 
				?>
			</div>
		<?php ActiveForm::end() ?>
	</div>
</div>