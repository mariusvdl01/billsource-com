<?php

use common\models\BusinessProfile;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BillsourceAsset;
$assetBundle = isset(Yii::$app->params['assetBundle']) ? Yii::$app->params['assetBundle'] : null;
$logo = $assetBundle->baseUrl.'/images/logo2.png';
$imagesPay = \frontend\assets\BillsourceAsset::register($this);

/* @var $this yii\web\View */
/* @var $client common\models\business\BusinessClient */
/* @var $profiles BusinessProfile[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-profile-upgrade">
	<div class="row">
		<div class="col-lg-12">
	    <?php $form = ActiveForm::begin([
	    		'id' => 'profile-upgrade-form',
	    		'layout' => 'horizontal',
	    		'options' => [
					'enctype' => 'multipart/form-data',
				],
				'action' => 'subscribe'
	    ]); ?>
		    <br />
		    <!-- <ul class="nav nav-tabs" role="tablist">
		    	<li role="presentation" class="active">
		    		<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a>
		    	</li>
		    </ul> -->
			<div class="tab-content">
			<br />
				 <div role="tabpanel" class="tab-pane fade in active" id="profile"> 
				 	<?= $form->field($client, 'profile_id')->dropDownList($profiles, ['prompt' => 'Select Profile']) ?>	
					<!-- <div id="do-details">
						<?= $form->field($client, 'debit_order_account')->label('Bank Account Number') ?>
					
					    <?= $form->field($client, 'debit_order_bank')->label('Bank Name') ?>
					
					    <?= $form->field($client, 'debit_order_branch')->label('Branch Name') ?>
					    
					    <?= $form->field($client, 'debit_order_branch_code')->label('Branch Code') ?>
					
					    <?= $form->field($client, 'debit_order_day')->dropdownList(range('1', '25'))
							->label('Day of Debit Order')
						?>
					</div> -->
				</div>
			</div>
			<div class="col-lg-12" id="do-details">
				<div class="col-lg-6 tags">
					<img src="<?= $logo ?>" class="center" alt="Billsource Logo" class="navbar-left logo" width="80" height="20"/>
					<p class="tagp"><b>Subscribe to plan: <span id="planname"></span></b></p>
					<p class="tagsubp">BY BILLSOURCE.COM</p>
					<div class="paystack-box text-center related">
						<p class="mb-4 absolute">🔒 Secured by <b>Paystack</b></p>
						<div class="payment-icons d-flex flex-wrap justify-content-center align-items-center">
						<img src="<?= $assetBundle->baseUrl . '/images/badges/mastercard-logo.svg';?>" alt="Mastercard"  style="width:28px; height:28px">
						<img src="<?= $assetBundle->baseUrl . '/images/badges/visa.svg';?>" alt="Visa">
						<img src="<?= $assetBundle->baseUrl . '/images/badges/american-express.svg';?>">
						<img src="<?= $assetBundle->baseUrl . '/images/badges/pay.svg';?>" alt="Apple Pay"></br>
						<img src="<?= $assetBundle->baseUrl . '/images/badges/ozow.svg';?>" alt="Ozow" style="width:40px; height:40px !important;">
						<img src="https://payfast.io/wp-content/uploads/2023/08/Ukheshe_scanToPay.svg" style="width:50px; height:50px; margin-left:5px;">
						<img src="https://payfast.io/wp-content/uploads/2023/01/SnapScan.svg" style="width:50px; height:50px;margin-left:5px;">
						</div>
					</div>
				</div>
				<div class="col-lg-6 tags2" class="text-center">You will be charged 12 monthly payment of ZAR <span id="planprice"></span> <br/>each</div>
					<div class="col-lg-3">
						<label>Name</label><span class="text-danger">*</span>
						<input type="hidden" name="profileid" id="profileid"/>
						<?php 
							$name = explode(" ",$client->contact_person);
						?>
						<input class="form-control" required name="firstname" placeholder="Enter Name" value="<?=$name[0]?>"/>
					</div>
					<div class="col-lg-3">
						<label>Last Name</label><span class="text-danger">*</span>
						<input class="form-control" required name="lastname" placeholder="Last Name" value="<?=$name[1]?>"/>
					</div>
					<div class="col-lg-6">
						<label>Email Address</label><span class="text-danger">*</span>
						<input class="form-control" name="email" required placeholder="Email Address" value="<?=$client->email?>"/>
					</div>
					<div class="col-lg-6 text-center">
						<p class="colorstyle"><i class="fa fa-info info"></i> We'll send you a confirmation email before each payment so you can cancel anytime.</p>
						<button type="submit" class="btn btn-success col-lg-12">Pay now & subscribe to this plan</button>
					</div>
					
			</div>
	   <?php ActiveForm::end(); ?>
	</div>
	</div>
	</div>
<style>
	.paystack-box {
      /* background-color: #fff; */
      border-radius: 5px;
      /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); */
      padding: 10px 5px;
      max-width: 250px;
      margin: 60px auto;
	  border:1px solid #ccc;
    }
    .payment-icons img {
      height: 30px;
    }
	.tags{
		padding-top: 80px;
		height:auto;
		background-color: #E5F2FC;
		text-align: center;
	}
	.center{
		display: block;
		margin-left: auto;
		margin-right: auto;
	}
	.tagp{
		text-align: center;
		margin-top: 10px;
	}
	.tagsubp{
		text-align: center;
		font-size: 10px;
		margin-top: 5px !important;
		margin-bottom: 200px;

	}
	.tags2{
		padding-top: 80px;
		height:auto;
		text-align: center;
		margin-bottom: 30px;
	}
	.related{
		position:relative;
	}
	.absolute{
		position:absolute;
		top:0%;
		left: 50%;
		transform: translate(-50%, -60%);
		background-color: #E5F2FC;
		font-size: 10px;
	}
	.colorstyle{
		margin-top: 15px;
		margin-bottom: 30px;
		padding-left: 25px;
		padding-right: 25px;
		color: #286090;
		font-size: 12px;
	}
	.info{
		border: 1px solid #99CCFF;
		/* border-radius: 20px; */
		border-radius: 100%;
		padding: 2px 4px;
		font-size: 8px;
	}
</style>