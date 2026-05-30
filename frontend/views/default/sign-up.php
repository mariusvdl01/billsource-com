<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - Sign up now';
$assetBundle = Yii::$app->params['assetBundle'];
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div id="quote">
			<div id="quote_right"></div>
			<blockquote>
				Collection and settlement of overdues has now<br /> become <strong>simpler</strong>,
				<strong>better</strong> and <strong>smarter</strong> than ever before,<br />
				– BillSource is the way of the future.
			</blockquote>
			<p>March 2012 | Founder and Owner | BillSource.com</p>
		</div>
		<!-- end of #quote -->

		<!-- start of #dues -->
		<div id="dues">

			<div id="double">
				<div id="arrows"></div>
			</div>

			<div id="block_left">
				<h2>Collect Your Dues</h2>
				<h3>Billers maintain a steady cash flow</h3>
				<div class="special">
					<h2>Sign-up Today</h2>
					<p>
						...and get <b>2 months<br /> free trial!
					</p>
				</div>
				<div id="btn_container" class="center">
					<img src="<?= $assetBundle->baseUrl ?>/images/icons/misc/maintaincashflow.png"
						alt="Maintain Cashflow" /> <br />
					<br />
					<!--<a class="btn" href="?tab=2">Register Now</a>-->
					<a class="btn" href="<?= Url::to(['account/signup', 'tab' => '2']) ?>">Register Now</a>
				</div>
				<ul>
					<li>Now, BillSource offers an alternative collection channel,
						reaching out to debtors who want to remain credit worthy, ready to
						settle their long overdue accounts, and ensure your cash flow
						remains steady.</li>
					<li>BillSource for business offers a self-service on- demand solution
						to load, monitor and track accounts receivables on long overdue
						accounts, no implementation hassles. Once registered loading bills
						is easy and quick.</li>
				</ul>
			</div>

			<div id="block_right">
				<h2>Pay Your Dues</h2>
				<h3>Individuals stay credit worthy</h3>
				<div class="special">
					<h2>Sign-up Today</h2>
					<p>
						and get <b>100 points</b><br />
						absolutely <b>FREE!</b>
					</p>
				</div>
				<div id="btn_container" class="center">
					<img src="<?= $assetBundle->baseUrl ?>/images/icons/misc/remainunlisted.png"
						alt="Remain Unlisted" /> <br />
					<br />
					<!--<a class="btn" href="?tab=3">Register Now</a>-->
					<a class="btn" href="<?= Url::to(['account/signup', 'tab' => '3']) ?>">Register Now</a>
				</div>
				<ul>
					<li>There is world-wide trend of high personal financial leverage
						putting more pressure on individuals already carrying huge financial
						burdens. More and more it leads to unhappy families.</li><br />
					<li>Billsource for individuals offer a self-service channel to
						conveniently monitor and settle long overdues prior to being handed
						over to debt collectors and prevent being listed with credit
						bureaus.</li>
				</ul>
			</div>

		</div>
		<!-- end of #dues -->
		<div class="clear"></div>

		<div id="sky_block">
			<div class="sky_block_content">
				<h2>SA's #1 Cloud Billing & Collections Channel</h2>
			</div>
			<!--//sky_block_content-->
		</div>
	</div>
</div>
