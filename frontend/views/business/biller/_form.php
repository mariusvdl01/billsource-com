<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;
use common\models\business\BusinessClient as Client;

/* @var $this yii\web\View */
/* @var $model common\models\individual\IndividualClient */
/* @var $form yii\widgets\ActiveForm */

$status = array(
	'1' => 'Yes',
	'0' => 'No'
)
?>

<div class="business-biller-form">
	<div class="row">
		<div class="col-lg-12">
	    <?php $form = ActiveForm::begin([
	    		'id' => 'profile-biller-form',
	    		'layout' => 'horizontal',
	    		'options' => [
					'enctype' => 'multipart/form-data',
				]
	    ]); ?>
		    <br />
		    <ul class="nav nav-tabs" role="tablist">
		    	<li role="presentation" class="active">
		    		<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile Details</a>
		    	</li>
		    	<li role="presentation">
		    		<a href="#contact-person" aria-controls="contact-person" role="tab" data-toggle="tab">Contact Person</a>
		    	</li>
		    	<li role="presentation">
		    		<a href="#contact-details" aria-controls="contact-details" role="tab" data-toggle="tab">Contact Details</a>
		    	</li>
				<?php if($model->profile_id != Client::PROFILE_FREE) : ?>
					<li role="presentation">
						<a href="#do-details" aria-controls="bill-requests" role="tab" data-toggle="tab">
							Bank Details
						</a>
					</li>
				<?php endif; ?>
		    </ul>
			<div class="tab-content">
			<br />
				 <div role="tabpanel" class="tab-pane fade in active" id="profile"> 	
				 	
				 	<?= $form->field($model, 'email') ?>

					<?= $form->field($model, 'active')->dropDownList($status)->label('Enabled') ?>
				 	
				    <?= $form->field($model, 'trading_name') ?>
				
				    <?= $form->field($model, 'registration_number') ?>
				
				    <?= $form->field($model, 'registered_name') ?>
				
				    <?= $form->field($model, 'vat_reg_number')->label('VAT Reg. Number') ?>
				    
				    <?= $form->field($model, 'business_logo')->widget(FileInput::className(), [
				    		'options' => ['accept' => 'image/*']
				    ]) ?>
				
				</div>
			    <div role="tabpanel" class="tab-pane fade" id="contact-person"> 	
				    <?= $form->field($model, 'title_id')->dropDownList($titles, [
				    		'prompt' => 'Select title'
				    ]) ?>
				    <?= $form->field($model, 'contact_person') ?>
				
				    <?= $form->field($model, 'initials') ?>
				
				    <?= $form->field($model, 'id_number')->label('ID Number') ?>
				
				</div>
				<div role="tabpanel" class="tab-pane fade" id="contact-details">
					<?= $form->field($model, 'address_street')->label('Street Address') ?>
				
				    <?= $form->field($model, 'address_region')->label('City/Suburb/Town') ?>
				
				    <?= $form->field($model, 'address_province')->dropDownList($provinces, [
				    		'prompt' => 'Select province'
				    ])->label('Province/State') ?>
				    
				    <?= $form->field($model, 'address_code')->label('Postal/ZIP Code') ?>
				
				    <?= $form->field($model, 'phone_number') ?>
				
				    <?= $form->field($model, 'fax_number') ?>
				</div>
				<?php if($model->profile_id != Client::PROFILE_FREE) : ?>
					<div role="tabpanel" class="tab-pane fade" id="do-details">
						<?= $form->field($model, 'debit_order_account')->label('Bank Account Number') ?>

						<?= $form->field($model, 'debit_order_bank')->label('Bank Name') ?>

						<?= $form->field($model, 'debit_order_branch')->label('Branch Name') ?>

						<?= $form->field($model, 'debit_order_branch_code')->label('Branch Code') ?>

						<?= $form->field($model, 'debit_order_day')->dropdownList(range('1', '25'))->label('Day of Debit Order')
						?>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-sm-offset-8">
				<?= Html::a('Cancel', ['/business/biller'], ['class'=>'btn btn-default']) ?>
				<?= Html::submitButton('Save', ['class' => 'btn btn-primary',]) ?>
			</div> 
	   <?php ActiveForm::end(); ?>
	</div>
	</div>
</div>