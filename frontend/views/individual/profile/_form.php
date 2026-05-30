<?php

use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $client common\models\individual\IndividualClient */
/* @var $form yii\widgets\ActiveForm */
/* @var $userBillRequest common\models\bill\UserBillRequest */
/* @var $billRequests array */
/* @var $provinces array */
?>

<div class="individual-profile-form">
	<div class="row">
		<div class="col-lg-12">
	    <?php $form = ActiveForm::begin([
	    	'id' => 'individual-profile-form',
	    	'layout' => 'horizontal',
	    	'options' => [
				'enctype' => 'multipart/form-data',
			]
	    ]); ?>
	    <br />
	    <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active">
		    	<a href="#bio-data" aria-controls="bio-data" role="tab" data-toggle="tab">Bio data</a>
		    </li>
		    <li role="presentation">
		    	<a href="#contact-details" aria-controls="contact-details" role="tab" data-toggle="tab">Contact Details</a>
		    </li>
		    <li role="presentation">
		    	<a href="#medical-aid" aria-controls="medical-aid" role="tab" data-toggle="tab">Medical Aid</a>
		    </li>
		    <li role="presentation">
		    	<a href="#bill-requests" aria-controls="bill-requests" role="tab" data-toggle="tab">Bills to Pay</a>
		    </li>
		</ul>
		<br />
		<div class="tab-content">
		    <div role="tabpanel" class="tab-pane fade in active" id="bio-data"> 	
			    <?= $form->field($client, 'title_id')->dropDownList($titles, [
			    		'prompt' => 'Select title'
			    ]) ?>
			    
			    <?= $form->field($client, 'email') ?>
			    
			    <?= $form->field($client, 'first_name') ?>
			
			    <?= $form->field($client, 'last_name') ?>
			
			    <?= $form->field($client, 'initials') ?>
			
			    <?= $form->field($client, 'id_number') ?>
			    
			    <?= $form->field($client, 'photo')->widget(FileInput::className(), [
    				'options' => ['accept' => 'image/*'],
				]) ?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="contact-details">
			    <?= $form->field($client, 'address_street')->label('Street Address') ?>
			
			    <?= $form->field($client, 'address_region')->label('City/Suburb/Town') ?>
			
			    <?= $form->field($client, 'province_id')->dropDownList($provinces, [
			    		'prompt' => 'Select province'
			    ])->label('Province/State') ?>
			    
			    <?= $form->field($client, 'address_code')->label('Postal/ZIP Code') ?>
			
			    <?= $form->field($client, 'home_telephone') ?>
			
			    <?= $form->field($client, 'office_telephone') ?>
			
			    <?= $form->field($client, 'mobile') ?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="medical-aid">
			    <?= $form->field($client, 'med_aid_name') ?>
			
			    <?= $form->field($client, 'med_aid_number') ?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="bill-requests">
				<?= $form->field($userBillRequest, 'user_id')->hiddenInput()->label(false) ?>
				<?= $form->field($userBillRequest, 'is_business_user')->hiddenInput()->label(false) ?>
				<?= $form->field($userBillRequest, 'request_id')->checkboxList($billRequests)->label('Bills to pay.') ?>
			</div>
			<div class="col-sm-offset-8">
				<?= Html::a('Cancel', ['/individual/profile'], ['class'=>'btn btn-default']) ?>
			    <?= Html::submitButton('Save profile', ['class' => 'btn btn-primary',]) ?>
			</div>
		</div> 
    	<?php ActiveForm::end(); ?>
    </div>
</div>
</div>