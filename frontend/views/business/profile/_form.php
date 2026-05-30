<?php

use common\models\business\BusinessClient as Client;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\individual\IndividualClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-profile-update">
	<div class="row">
		<div class="col-lg-12">
	    <?php $form = ActiveForm::begin([
	    		'id' => 'profile-update-form',
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
		    	<li role="presentation">
		    		<a href="#bill-requests" aria-controls="bill-requests" role="tab" data-toggle="tab">Bills to Pay</a>
		    	</li>
				<?php if($client->profile_id != Client::PROFILE_FREE) : ?>
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
				 	
				 	<?= $form->field($client, 'email') ?> 
				 	
				    <?= $form->field($client, 'trading_name') ?>
				
				    <?= $form->field($client, 'registration_number') ?>
				
				    <?= $form->field($client, 'registered_name') ?>
				
				    <?= $form->field($client, 'vat_reg_number')->label('VAT Reg. Number') ?>
				    
				    <?= $form->field($client, 'business_logo')->widget(FileInput::className(), [
				    		'options' => ['accept' => 'image/*']
				    ]) ?>
				
				</div>
			    <div role="tabpanel" class="tab-pane fade" id="contact-person"> 	
				    <?= $form->field($client, 'title_id')->dropDownList($titles, [
				    		'prompt' => 'Select title'
				    ]) ?>
				    <?= $form->field($client, 'contact_person') ?>
				
				    <?= $form->field($client, 'initials') ?>
				
				    <?= $form->field($client, 'id_number')->label('ID Number') ?>
				
				</div>
				<div role="tabpanel" class="tab-pane fade" id="contact-details">
					<?= $form->field($client, 'address_street')->label('Street Address') ?>
				
				    <?= $form->field($client, 'address_region')->label('City/Suburb/Town') ?>
				
				    <?= $form->field($client, 'province_id')->dropDownList($provinces, [
				    		'prompt' => 'Select province'
				    ])->label('Province/State') ?>
				    
				    <?= $form->field($client, 'address_code')->label('Postal/ZIP Code') ?>
				
				    <?= $form->field($client, 'phone_number') ?>
				
				    <?= $form->field($client, 'fax_number') ?>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="bill-requests">
					<?= $form->field($userBillRequest, 'user_id')->hiddenInput()->label(false) ?>
					<?= $form->field($userBillRequest, 'is_business_user')->hiddenInput()->label(false) ?>
					<?= $form->field($userBillRequest, 'request_id')->checkboxList($billRequests)
					->label('Bills you would like to pay.') ?>
				</div>
				<?php if($client->profile_id != Client::PROFILE_FREE) : ?>
					<div role="tabpanel" class="tab-pane fade" id="do-details">
						<?= $form->field($client, 'debit_order_account')->label('Bank Account Number') ?>

						<?= $form->field($client, 'debit_order_bank')->label('Bank Name') ?>

						<?= $form->field($client, 'debit_order_branch')->label('Branch Name') ?>

						<?= $form->field($client, 'debit_order_branch_code')->label('Branch Code') ?>

                        <?= $form->field($client, 'debit_order_day')->widget(\kartik\touchspin\TouchSpin::className(), [
                            'pluginOptions' => [
                                'min' => 1,
                                'max' => 25,
                                'step' => 1,
                                'verticalbuttons' => true,
                                'verticalupclass' => 'glyphicon glyphicon-plus',
                                'verticaldownclass' => 'glyphicon glyphicon-minus',
                            ],
                            'options' => ['placeholder' => 'Adjust Cost Price...']
                        ]) ?>
                        <?php #$form->field($client, 'debit_order_day')->dropdownList(range('1', '25'))->label('Day of Debit Order') ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-sm-offset-8">
				<?= Html::a('Cancel', ['/business/profile'], ['class'=>'btn btn-default']) ?>
				<?= Html::submitButton('Save profile', ['class' => 'btn btn-primary',]) ?>
			</div> 
	   <?php ActiveForm::end(); ?>
	</div>
	</div>
</div>