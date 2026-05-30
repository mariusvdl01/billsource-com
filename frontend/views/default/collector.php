<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - Collector';
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div id="sky_block">
			<h2>Streamline debt collection or counselling</h2>
			<p>In a tough business environment businesses require a reliable bill
				generation and collection solution</p>
		</div>
		<!-- end of #sky_block -->
		<div id="button_container">
			<a href="<?= Url::to(['account/signup', 'tab' => $tab]) ?>">Sign-up as Collector</a>
		</div>
		<!-- start of #signup -->
		<div class="signup">
			<h2>Be part of the online bill collection solution</h2>
			<h3>Get ahead with our Services</h3>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Focus on the collection of debt</li>
					<li>Get your customers online</li>
					<li>Differentiate your business</li>
					<li>Get notified of bill(s) that</li>
					<li>Improve your customer experience</li>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Build your brand and reputation</li>
					<li>Maintain a solid customer relationship</li>
					<li>Reduce your accounts receivables</li>
					<li>Improve your credit position</li>
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
