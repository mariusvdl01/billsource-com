<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - Counsellor';
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div id="sky_block">
			<h2>Debt Rescue Services</h2>
			<p>Billsource offer Individuals and business online debt managing
				service. Our network of debt experts provides payment relief to
				thousands of customers, big or small.</p>
		</div>
		<!-- end of #sky_block -->
		<div id="button_container">
			<a href="<?= Url::to(['account/signup', 'tab' => $tab]) ?>">Sign-up as counsellor</a>
		</div>
		<!-- start of #signup -->
		<div class="signup">
			<h2>Reasons to register</h2>
			<h3>Get ahead with our Services. Focusing on the debt counseling</h3>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Differentiate your service</li>
					<li>Get notified of individuals that require counseling</li>
					<li>Get details about the individuals.</li>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Get leads in your area about individuals that require counseling</li>
					<li>Get information about the debt postion of the individual</li>
				</ul>
			</div>
		</div>
		<!-- end of #signup -->
		<div id="sky_block_2">
			<h2>CEO, Debt Console, Durban</h2>
			<p>"We are helping individuals struggling with debt. BillSource makes
				it possible for us to reach new customers daily"</p>
		</div>
	</div>
</div>