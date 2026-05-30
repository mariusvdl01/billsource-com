<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - Billers';
?>

<div class="panel panel-default">
	<div class="panel-body">
			<div id="sky_block">
				<h2>Maintain a Steady Cash Flow</h2>
				<p>Now, BillSource offers an alternative collection channel, reaching
					out to debtors who want to remain credit worthy, ready to settle their
					long overdue accounts, and ensure your cash flow remains steady.</p>
			</div>
		<!-- end of #sky_block -->
		<div id="button_container">
			<!--<a href="pub-business-register.php">Sign-up as a Biller</a>-->
			<a href="<?= Url::to(['account/signup', 'tab' => $tab]) ?>">Sign-up as a Biller</a>
		</div>
		<div id="value">
			<h2>Value Network</h2>
			<ul>
				<li>BillSource brings a network of Debt Collectors, Counsellors, and
					FSPs together to make money move faster. Our System of Engagement is
					specifically designed to bring related business activities together
					that fit together. Get rid of those time consuming collections
					activities and let the network work for you while you keep full
					visibility of progress collecting on late payments. Debt Counselors
					form part of our network and assist individuals who want to reach out
					and start to manage personal debt better. Our network also includes
					Financial Service Providers that underwrite exclusive deals to a
					community who are managing debt responsibly, for both the business
					and for individuals.</li>
			</ul>
		</div>
		<!-- start of #signup -->
		<div class="signup">
			<h2>Sign-up as a Business User</h2>
			<h3>More reasons to sign-up</h3>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Become paperless and environmentally friendly</li>
					<li>100% savings on printing and administration</li>
					<li>Affordable subscription and transaction fees</li>
					<li>Up to 10% saving on collector and lawyer fees</li>
					<li>Reach an online community ready to settle dues</li>
					<li>Market directly to your customers</li>
					<li>Offer discounts on long overdue accounts</li>
					<li>Automated dunning and lawyers letters</li>
					<li>Reduce collection period from months to days</li>
					<li>100% reduction in reconciliation errors</li>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>You are notified every time when you log in</li>
					<li>Secure environment with full system audit trails</li>
					<li>Update your profile when your details change</li>
					<li>Vet future Business Partners and Customers</li>
					<li>Load invoicing information quick and easy</li>
					<li>Monitor payments as and when they happen</li>
					<li>We make use of an accredited payments gateway</li>
					<li>Maintain your cash flow position</li>
					<li>On-demand solution without capital expense</li>
					<li>Request system changes that you need</li>
					<li>Technical support during office hours</li>
				</ul>
			</div>
		</div>
		<!-- end of #signup -->
		<div id="sky_block_2">
			<h2>Kenneth Onah, SME Owner, Gauteng</h2>
			<p>Since we've moved to cloud billing and collections, our cash flow
				have improved. BillSource breaks down barriers between debtors and
				creditors</p>
		</div>
	</div>
</div>
