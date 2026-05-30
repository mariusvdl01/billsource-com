<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $invoice common\models\invoice\Invoice */
/* @var $form yii\widgets\ActiveForm */

$now = date('Y-m-d', time());
?>

<div class="cash-invoice-form">
	<div class="row">
		<div class="col-sm-12">
			<div class="bill-entry">
		    <?php $form = ActiveForm::begin([
		    	'id' => 'invoice-form',
		    	'options' => [
		    		'enctype' => 'multipart/form-data',
		    	],
		    	'fieldConfig' => [
		    		'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}",
		    		'horizontalCssClasses' => [
		    			'label' => '',
		    			'offset' => '',
		    			'wrapper' => 'col-sm-8',
		    			'error' => '',
		    			'hint' => '',
		    		],
		    	],
		    ]); ?>
	    	<table class="col-sm-12">
					<tr>
						<td colspan="4">
							<table class="col-sm-12">
								<tr>
                 					<td><h4>From</h4></td>
                 					<td><?= $form->field($biller, 'id')->hiddenInput()->label(false)?></td>
                 				</tr>
								<tr>
									<td><strong>Trading Name:</strong></td>
									<td><?php echo (isset($biller->trading_name)) ? $biller->trading_name : 'Business Client'; ?></td>
			                 		<td>Internal Ref. No.&nbsp;&nbsp;</td>
			                 		<td><?= $invoice->reference_number ?></td>
		                 		</tr>
		                 		<tr>
		                 			<td>VAT Reg. #</td>
									<td id="business_vat"><?= isset($biller->vat_reg_number) ? $biller->vat_reg_number : "" ?></td>
					                <td>Issue Date:</td>
					                <td><?= !empty($invoice->issue_date) ? $invoice->issue_date : $now ?>
				                </tr>
				                <tr>
				                 	<td>&nbsp;</td>
				                 	<td>&nbsp;</td>
				                </tr>
					            <tr>
					            <?php if(!$invoice->isNewRecord) : ?>
				                 	<td class="text-center">Status</td>
				                 	<td><?= $form->field($invoice, 'status_id')->dropdownList($statuses)->label(false)?></td>
				                <?php endif; ?>
				                 	<td>&nbsp;</td>
				                 	<td>&nbsp;</td>
				                </tr>
				                <tr>
				                 	<td><h4>To</h4></td>
				                 	<td>&nbsp;</td>
				                 	<td><font color="#CC0000">*</font> Required fields</p></td>
				                </tr>
				                <tr>
					                <td></td>
					                <td></td>
				                </tr>
				                <tr>
                 					<td class="text-center">Reference Number<font color="#CC0000">*</font></td>
                 					<td><?= $form->field($invoice, 'reference_number', [
		    								'inputOptions' => [
		    									'class'		=> 'form-control',
		    								]
		   								])->textInput(['readonly' => true])->label(false) ?>
		    						</td>
                 					<td class="text-center">Terms<font color="#CC0000">*</font></td>
                 					<td><?= $form->field($invoice, 'comments')->textInput(['readonly' => true, 'value' => 'Cash'])->label(false) ?></td>
                 				</tr>
                 				<tr>
                 					<td class="text-center">Customer Name<font color="#CC0000">*</font></td>
                 					<td><?= $form->field($invoice, 'alt_business_name', [
		    								'inputOptions' => [
		    									'class'	=> 'form-control',
		    								]
		   								])->textInput(['readonly' => true, 'value' => 'Cash'])->label(false) ?>
		   							</td>
				                 	<td class="text-center">Due Date<font color="#CC0000">*</font></td>
				                 	<td><?= $form->field($invoice, 'due_date')->label(false)->widget(DatePicker::className(), [ 
											    'value' => '',
											    'options' => ['placeholder' => 'Select due date ...'],
											    'pluginOptions' => [
											        'format' => 'yyyy-m-d',
											        'todayHighlight' => true
											    ]
                 						]) ?>
                 					</td>
				                </tr>
				                <tr>
									<td class="text-center">Tel / Mobile<font color="#CC0000">*</font></td>
									<td><?= $form->field($invoice, 'client_mobile', [
											'inputOptions' => [
												'class' 	  => 'form-control',
											]
										])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false)?></td>
									<td class="text-center"></td>
									<td></td>
				                </tr>
				                <tr>
				                	<td class="text-center">Email<font color="#CC0000">*</font></td>
				                	<td><?= $form->field($invoice, 'client_email', [
		    								'inputOptions' => [
		    									'class' 	  => 'form-control',
		    								]
		    							])->textInput(['readonly' => $invoice->isNewRecord ? false : true])->label(false) ?>
		    						</td>
                 					<td class="text-center"></td>
                 					<td colspan="2"></td>
				                </tr> 
							</table>
							<table id="items" class="col-sm-12">
				                <thead>
				                	<tr>
                                        <td class="text-center" style="width:100%;margin:0 5px
	                 					!important"><b>Product/Service Description</b><font
                                                    color="#CC0000">*</font></td>
	                 					<td class="text-center"><b>Quantity</b><font color="#CC0000">*</font></td>
	                 					<td class="text-center"><b>Unit Price</b><font color="#CC0000">*</font></td>
	                 					<td class="text-center"><b>Amount</b></td>
	                 					<td class="text-center"><b>Action</b></td>
	                 				</tr>
                 				</thead>
                 				<tbody id="items">
	                 				<?php foreach ($lineManager->getItems() as $id => $model) : ?>
	                 					
	            							<?= $this->render('_item', [
	            									'id' 			=> $id, 
	            									'model' 		=> $model, 
	            									'form' 			=> $form, 
	            									'lineManager' 	=> $lineManager,
													'products'		=> $products,
	            							]) ?>
	            						
            						<?php endforeach; ?>
            					</tbody>
            					<tfoot>						
	                 				<tr>
                 						<td></td>
                 						<td></td>
                 						<td align="right">Discount&nbsp;&nbsp;</td>
                 						<td><?= $form->field($invoice, 'discount')->textInput([
                 								'value' => $invoice->isNewRecord ? 0 : $invoice->discount,
                 								'style'	=> 'width:120px;margin:0 5px',
                 						])->label(false) ?></td>
                 						<td></td>
                 						<td></td>
                 					</tr>
                 					<tr>
						                <td></td>
						                <td><?= $form->field($invoice, 'amount')->hiddenInput()->label(false) ?></td>
						                <td align="right">Sub Total&nbsp;&nbsp;</td>
						                <td><?= $form->field($invoice, 'subtotal')->textInput([
						                		'readonly'	=> true,
						                		'style'	=> 'width:120px;margin:0 5px',
						                ])->label(false) ?>
						                </td>
						                <td></td>
						                <td></td>
					                </tr>
						            <tr>
					                 	<td></td>
					                 	<td></td>
					                 	<td align="right">V.A.T. @ 14%&nbsp;&nbsp;</td>
					                 	<td><?= $form->field($invoice, 'vat')->textInput([
					                 			'readonly' => true,
					                 			'style'	=> 'width:120px;margin:0 5px',
					                 		])->label(false) ?>
					                 	</td>
					                 	<td></td>
					                 	<td></td>
					                </tr>
					                <tr>
										<td class="text-center"></td>
										<td></td>
										<td align="right" style="color:green"><strong>Total&nbsp;&nbsp;</strong></td>
										<td><?= $form->field($invoice, 'total')->textInput([
												'readonly' => true,
												'style'	=> 'width:120px;margin:0 5px',
											])->label(false) ?>
										</td>
										<td></td>
										<td></td>
					                </tr> 
								</tfoot> 
	                 		</table>
						</td>
					</tr>
				</table>
			    <div class="form-group">
			    	<?= Html::a(Yii::t('app', 'Never mind'), ['/business/invoice'], ['class'=>'btn btn-default']) ?>
			      	<?= Html::a(Yii::t('app', 'Add Item'), '#', [
			        		'class'=>'btn btn-default',
           					'params'	=> [
           						'InvoiceLine[command]'	=> 'add',
           					],
			        		'onClick' 		=> 'addItem(); return false;',
           				]
           			);?>
           			<?= Html::resetButton(Yii::t('app', 'Clear'), ['class' => 'btn btn-primary']) ?>
           			<?= Html::submitButton(
			        	$invoice->isNewRecord ? Yii::t('app', 'Create Invoice') : Yii::t('app', 'Update Invoice'), 
			        	['class' => $invoice->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) 
			        ?>
			    </div>
			    <?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>