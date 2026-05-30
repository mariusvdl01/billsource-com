<?php
use yii\helpers\Url;
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */
$this->title = 'Billsource - Value Added Service';
?>
<div class="panel panel-default">
	<div class="panel-body">
		<div id="sky_block">
			<h2>Add value to your customer base</h2>
			<p>In a tough business environment you need to stay ahead of the
				competition. Offer your customer a one-stop-shop to view and pay their
				dues.</p>
		</div>
		<!-- end of #sky_block -->
		<div id="button_container">
			<!--<a href="pub-business-register.php?tab=5">Get your customer to sign-up</a>-->
			<a href="<?= Url::to(['account/signup', 'tab' => $tab]) ?>">Get your customer to sign-up</a>
		</div>
		<!-- start of #signup -->
		<div class="signup">
			<h2>Value Added Service in a competitive world</h2>
			<h3>Get ahead with our Services</h3>
			<ul>
				<li>Get your customers online</li>
				<li>Differentiate your business</li>
				<li>Compete with corporate businesses</li>
				<li>Improve your customer experience</li>
			</ul>
			<ul>
				<li>Maintain a solid customer relationship</li>
				<li>Reduce your accounts receivables</li>
				<li>Improve your credit position</li>
				<li>Build your brand and reputation</li>
			</ul>
		</div>
		<!-- end of #signup -->
		<div id="sky_block_2">
			<h2>Marcel Dewitt, Advertising, CT</h2>
			<p>"As a small business we are competing with large enterprises.
				BillSource make us look professional"</p>
		</div>
	</div>
</div>