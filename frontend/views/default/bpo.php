<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - Business Process Outsourcing';
?>

<div class="panel panel-default">
	<div class="panel-body">
		<!-- start of #sky_block -->
		<div id="sky_block">
			<h2>Outsource non-core Business Activities</h2>
			<p>As a small business starting up or growing, you must concentrate on
				your core business. Consider outsoucing your Account Management
				administration and focus on sales instead.</p>
		</div>
		<!-- end of #sky_block -->
		<div id="button_container">
			<!--<a href="pub-business-register.php?tab=4">Improve account management today</a>-->
			<a href="<?= Url::to(['account/signup', 'tab' => $tab]) ?>">Improve account management today</a>
		</div>
		<div id="value">
			<h2>Debt Clerk Agency - DCA</h2>
			<ul>
				<li>Extend your business with BillSource DCA Program for accountants
					and bookkeepers. Make money move faster by capturing your customer's
					invoices into the systems and linking in with the BillSource Value
					Network. Obtain additional revenue streams and extend your business
					reach using the easy-to-use web-portal.</li>
			</ul>
		</div>
		<!-- start of #signup -->
		<div class="signup">
			<h2>
				Business Process Outsourcing Made Easy
				</h2>
				<h3>More reasons to Outsource</h3>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Spend time on selling more</li>
					<li>Reduce administrative overheads by 30%</li>
					<li>Extend your accounting capability</li>
					<li>Improve your invoicing and GL processes</li>
					<li>Maintain a 24hr view on cashflow</li>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<ul>
					<li>Reduce your capital expenditure</li>
					<li>Reduce your headcount</li>
					<li>Involve the experts in the field</li>
					<li>Improve your customer experience</li>
					<li>Present a professional channel</li>
				</ul>
			</div>
		</div>
		<!-- end of #signup -->
		<div id="sky_block_2">
			<h2>Sarah Conner, Accountant, PTA</h2>
			<p>We extended our services to our clients and have received positive
				feedback helping them make money move faster. In the process we have
				added additional income streams and won new business. The DCA
				programme works well in our business.</p>
		</div>
	</div>
</div>