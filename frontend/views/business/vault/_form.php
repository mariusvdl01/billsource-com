<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $invoice common\models\invoice\Invoice */
/* @var $form yii\widgets\ActiveForm */

$now = date('Y-m-d', time());
?>

<div class="vault-invoice-form">
	<div class="row">
		<div class="col-lg-12">
			<div class="bill-entry">
		    <?php $form = ActiveForm::begin([
		    	'id' => 'invoice-form',
		    	'options' => [
		    		'enctype' => 'multipart/form-data',
		    	],
		    	'fieldConfig' => [
		    		'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
		    		'horizontalCssClasses' => [
		    			'label' => '',
		    			'offset' => '',
		    			'wrapper' => 'col-sm-8',
		    			//'error' => '',
		    			'hint' => '',
		    		],
		    	],
		    ]); ?>
	    	<table class="col-sm-12">
					<tr>
						<td colspan="4">
							<table class="col-sm-12">
								<tr>
                 					<td><h3>From</h3></td>
                 					<td><?= $form->field($biller, 'id')->hiddenInput()->label(false)?></td>
                 				</tr>
								<tr>
									<td><strong>Trading Name:</strong></td>
									<td><?php echo (isset($biller->trading_name)) ? $biller->trading_name : 'Business Client'; ?></td>
			                 		<td>Internal Ref. No.&nbsp;&nbsp;</td>
			                 		<td>BILLINV-<?= $invoice->id ?></td>
		                 		</tr>
		                 		<tr>
		                 			<td>VAT Reg. #</td>
									<td><?= isset($biller->vat_reg_number) ? $biller->vat_reg_number : "" ?></td>
					                <td>Issue Date:</td>
					                <td><?= !empty($invoice->issue_date) ? $invoice->issue_date : $now ?>
				                </tr>
					            <tr>
				                 	<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
				                </tr>
				                <tr>
				                 	<td><h3>To</h3></td>
				                 	<td>&nbsp;</td>
				                 	<td>&nbsp;</td>
				                </tr>
				                
				                <tr>
                 					<td class="text-center">Reference Number<font color="#CC0000">*</font></td>
                 					<td><?= $form->field($invoice, 'reference_number', [
		    								'inputOptions' => [
		    									'class' 	  => 'form-control',
		    								]
		   								])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false) ?>
		    						</td>
                 					<td class="text-center">VAT Reg. No.</td>
                 					<td><?= $form->field($invoice, 'client_vat', [
		    								'inputOptions' => [
		    									'class' 	  => 'form-control',
		    								]
		    							])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false) ?></td>
                 				</tr>
                 				<tr>
                 					<td class="text-center">Customer Name<font color="#CC0000">*</font></td>
                 					<td><?= $form->field($invoice, 'alt_business_name', [
		    								'inputOptions' => [
		    									'class' 	  => 'form-control',
		    								]
		   								])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false) ?>
		   							</td>
				                 	<td class="text-center">ID / Reg No.<font color="#CC0000">*</font></td>
				                 	<td><?= $form->field($invoice, 'client_id', [
		    							'inputOptions' => [
		    								'class' 	  => 'form-control',
		    							]
		    						])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false) ?></td>
				                </tr>
				                <tr>
				                 	<td class="text-center">Mobile<font color="#CC0000">*</font></td>
				                 	<td><?= $form->field($invoice, 'client_mobile', [
		    							'inputOptions' => [
		    								'class' 	  => 'form-control',
		    							]
		    						])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false)?></td>
				                </tr>
				                <tr>
				                	<td class="text-center">Email<font color="#CC0000">*</font></td>
				                	<td><?= $form->field($invoice, 'client_email', [
		    								'inputOptions' => [
		    									'class' 	  => 'form-control',
		    								]
		    							])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false) ?>
		    						</td>
                 					<td class="text-center">Due Date<font color="#CC0000">*</font></td>
                 					<td colspan="2">
                 						<?= $form->field($invoice, 'due_date')->label(false)->widget(DatePicker::className(), [
											
                 						]) ?>
									</td>
				                </tr> 
				                <tr>
                 						<td class="text-center">Marketing</td>
                 						<td><?= $form->field($invoice, 'marketing', [
		    								'inputOptions' => [
		    									'class' 	  => 'form-control',
		    								]
		    							])->label(false) ?></td>
                 						<td align="right"></td>
                 						<td></td>

                 					</tr>
                 					<tr>
					                 	<td class="text-center">Terms<font color="#CC0000">*</font></td>
					                 	<td><?= $form->field($invoice, 'comments')->label(false) ?></td>
					                 	<td class="text-center">Upload file<font color="#CC0000">*</font></td>
					                 	<td><?= $form->field($invoice, 'pdf')->fileInput()->label(false) ?></td>
					                </tr>
							</table>
						</td>
					</tr>
				</table>
			    <div class="form-group">
			    	<?= Html::a(Yii::t('app', 'Never mind'), ['/business/invoice'], ['class'=>'btn btn-default']) ?>
           			<?= Html::resetButton(Yii::t('app', 'Clear'), ['class' => 'btn btn-primary']) ?>
           			<?= Html::submitButton(
			        	$invoice->isNewRecord ? Yii::t('app', 'Vault Invoice') : Yii::t('app', 'Update Vault'), 
			        	['class' => $invoice->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) 
			        ?>
			    </div>
			    <?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
