<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - Individuals';
$assetBundle = Yii::$app->params['assetBundle'];
?>
<div class="panel panel-default">
	<div class="panel-body">
		<div id="sky_block">
			<h2>Individuals Stay Credit Worthy</h2>
			<p>Billsource for individuals offer a self-service channel to
				conveniently monitor and settle long overdue accounts prior to being
				handed over to debt collectors and prevent being listed with credit
				bureaus.</p>
		</div>
		<!-- end of #sky_block -->
		<div id="button_container">
			<!--<a href="pub-ind-register.php">Sign-up as a Individual User</a>-->
			<a href="<?= Url::to(['account/signup', 'tab' => $tab]) ?>">Sign-up as an Individual</a>
		</div>
		<!-- start of #signup -->
		<div class="signup">
			<h2>Sign-up as a Personal User</h2>
			<h3>More reasons to sign-up</h3>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Save on interest and penalties for late payment</li>
					<li>Improve your payment profile by settling dues</li>
					<li>Obtain a unique password when you activate</li>
					<li>Secure environment so your information is safe</li>
					<li>Update your profile when things change</li>
					<li>View all your outstanding dues in one place</li>
					<li>Receive discounts on long overdue accounts</li>
					<li>Make easy payments without making errors</li>
					<li>We do not store your credit card details</li>
					<li>Make payment arrangements directly with billers</li>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Maintain your assets versus liabilities</li>
					<li>Check for Identity Theft</li>
					<li>Calculate and manage your debt ratio</li>
					<li>Earn rewards and redeem against our catalogue</li>
					<li>Win partial or full settlement of all your dues</li>
					<li>Ask assistance from a registered debt counsellor</li>
					<li>Maintain your budget and know your limits</li>
					<li>Start reducing your debt and stress immediately</li>
					<li>Simple PC and Tablet friendly screen layout</li>
					<li>Become part of a select community with access to preferential
						rates and exclusive offers</li>
				</ul>
			</div>
		</div>
	</div>
</div>