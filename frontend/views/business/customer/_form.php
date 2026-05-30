<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\business\BusinessClientCrm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-client-crm-form">
	<div class="row">
		<div class="col-lg-12">
		    <?php $form = ActiveForm::begin([
		    	'id' => 'business-client-crm-form',
		    	'layout' => 'horizontal'
		    ]); ?>
				<?= $form->field($model, 'business_id')->hiddenInput([
						'value' => $business_id
				])->label(false)?>
				<ul id="nav-tabs" class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
			    		<a href="#cust-type" aria-controls="cust-type" role="tab" data-toggle="tab">Customer</a>
			    	</li>
			    	<li role="presentation">
			    		<a href="#bio-data" aria-controls="bio-data" role="tab" data-toggle="tab">Contact</a>
			    	</li>
			    	<li role="presentation">
			    		<a href="#contact-details" aria-controls="contact-details" role="tab" data-toggle="tab">Address Details</a>
			    	</li>
			    	<li role="presentation">
			    		<a href="#business-details" aria-controls="business-details" role="tab" data-toggle="tab">Business Details</a>
			    	</li>
		    	</ul>
		    	<div class="tab-content">
				<br />
					<div role="tabpanel" class="tab-pane fade in active" id="cust-type"> 
						<?= $form->field($model, 'is_business')->dropdownList([
					    		'1'	=> 'Business',
					    		'0'	=> 'Individual',
					    ])->label('Type') ?>
					    
					    <?= $form->field($model, 'is_active')->dropdownList([
					    		'1'	=> 'Enabled',
					    		'0'	=> 'Disabled',
					    ]) ?>
					    
					    <?= $form->field($model, 'email') ?>
					    
					   	<?= $form->field($model, 'mobile') ?>
					</div>
		    		<div role="tabpanel" class="tab-pane fade" id="bio-data"> 
					    <?= $form->field($model, 'first_name') ?>
					
					    <?= $form->field($model, 'last_name') ?>
					    
					    <?= $form->field($model, 'id_number') ?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="contact-details"> 	
					    <?= $form->field($model, 'address_street') ?>
					
					    <?= $form->field($model, 'address_region') ?>
					
					    <?= $form->field($model, 'province_id')->dropdownList($provinces) ?>
					
					    <?= $form->field($model, 'address_code') ?>
						
						<?= $form->field($model, 'phone_number') ?>
						
					    <?= $form->field($model, 'fax_number') ?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="business-details"> 
					    <?= $form->field($model, 'trading_name') ?>
					
					    <?= $form->field($model, 'registration_number') ?>
					
					    <?= $form->field($model, 'registered_name') ?>
					
					    <?= $form->field($model, 'vat_reg_number') ?>
					</div>
				</div>
				<div class="col-sm-offset-8">
					<?= Html::a('Cancel', ['/business/customer'], ['class'=>'btn btn-default']) ?>
						<?= Html::submitButton(
				       			 $model->isNewRecord ? Yii::t('app', 'Add Customer') : Yii::t('app', 'Save Changes'), 
				        			['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
					?>
				</div>
		    <?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
