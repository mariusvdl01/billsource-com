<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Billsource - All your bills in one place';
$assetBundle = Yii::$app->params['assetBundle'];
\frontend\assets\MatchboxAsset::register($this);
?>

<div class="dropdown visible-xs">
	<button class="dropbtn" onclick="myFunction()"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
	<div class="dropdown-content" id="myDropdown">
		<a href="<?= Url::to(['default/home', 'tab' => '8']) ?>">Promotions</a> 
		<a href="<?= Url::to(['default/home', 'tab' => '1']) ?>">Home</a> 
		<a href="<?= Url::to(['default/home', 'tab' => '2']) ?>">Billers</a> 
		<a href="<?= Url::to(['default/home', 'tab' => '3']) ?>">Individuals</a> 
		<a href="<?= Url::to(['default/home', 'tab' => '4']) ?>">DCA</a> 
		<a href="<?= Url::to(['default/home', 'tab' => '5']) ?>">Counsellor</a> 
		<a href="<?= Url::to(['default/home', 'tab' => '6']) ?>">Collector</a> 
		<a href="<?= Url::to(['default/contact']) ?>">Contact us</a>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row charts">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><img class="img-responsive img-rounded" src="<?= $assetBundle->baseUrl ?>/images/BNR_lp-home_01.jpg" style="margin-bottom:15px;"></div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row center-block">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="c100 p87 big center">
									<span>87%</span>
									<div class="slice">
										<div class="bar"></div>
										<div class="fill"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h4>Loosing track of outstanding invoices</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row center-block">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="c100 p95 big center">
									<span>95%</span>
									<div class="slice">
										<div class="bar"></div>
										<div class="fill"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h4>Struggling to manage rising debt</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row center-block">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="c100 p63 big center">
									<span>63%</span>
									<div class="slice">
										<div class="bar"></div>
										<div class="fill"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h4>Considering counselling or financing</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><img class="img-responsive img-rounded" src="<?= $assetBundle->baseUrl ?>/images/BNR_lp-home_02.jpg"></div>
		</div>
		<div id="footer_nav_fluid" style="margin:0 -10px; padding-top:20px;">
			<div class="row center-block" style="line-height:normal;">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="panel panel-primary"><div class="panel-heading"><h3 class="text-center panel-title" style="line-height:normal;">Simpler</h3></div></div>
					
					<p style="line-height:normal;"><b>Now you have secure access</b></p>
					<ul style="line-height:normal;">
						<li style="line-height:normal;">You will stay credit worthy especially in tough economic times<br></li>
						<li style="line-height:normal;">No other channel offers the full spectrum of bills to individuals in a single convenient online self-service channel<br></li>
						<li style="line-height:normal;">You can now track overdues prior to being handed over or being listed providing them with an opportunity to settle</li>
					</ul>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="panel panel-primary"><div class="panel-heading"><h3 class="text-center panel-title" style="line-height:normal;">Better</h3></div></div>
					
					<p style="line-height:normal;"><b>Existing channels are not always effective</b></p>
					<ul style="line-height:normal;">
						<li style="line-height:normal;">Conventional e-Mail and SMS may no longer prove successful especially with customer details changing constantly<br></li>
						<li style="line-height:normal;">e-Mail and SMS are lost as individuals are bombarded with marketing, business and personal communications<br></li>
						<li style="line-height:normal;">You can securely track overdues and will be notified of accounts ageing to promote early settlements</li>
					</ul>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="panel panel-primary"><div class="panel-heading"><h3 class="text-center panel-title" style="line-height:normal;">Smarter</h3></div></div>
					
					<p style="line-height:normal;"><b>You now have tools and incentives to manage bills</b></p>
					<ul style="line-height:normal;">
						<li style="line-height:normal;">Debtors are incentivised to settle overdues by earning points redeemable against a catalogue of consumer goods<br></li>
						<li style="line-height:normal;">Individuals can monitor their financial position and also request the services of debt counselling<br></li>
						<li style="line-height:normal;">Businesses achieve increased cashflow</li>
					</ul>
				</div>
			</div>
		</div>
		<!--<hr style="border-bottom:2px dotted #0E76BC;">-->
		<div class="clear"></div>
		<div class="panel panel-default" style="background: #d9edf7; border: none">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
						<h3><img style="width:48px;" src="<?= $assetBundle->baseUrl ?>/images/icon-only_billsource_orange.png"> "We introduce order so <span style="color:#EEAA44;">you</span> can save <span style="color:#EEAA44;">time and money</span>"
							<button type="button" id="myButton" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
						  <a href="<?= Url::to(['account/signup']) ?>" class="signup-link">Sign up NOW</a>
						</button></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="row img-panel" style="margin-top:10px;">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="hidden-xs hidden-sm col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1"><img src="<?= $assetBundle->baseUrl ?>/images/business_icon04.png"></div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Individuals Stay Credit Worthy</h3>
							</div>
							<div class="panel-body">
								<ul>
									<li>Save on interest and penalties for late payment</li>
									<li>Improve your payment profile by settling dues</li>
									<li>View all your outstanding dues in one place</li>
									<li>Receive discounts on long overdue accounts</li>
									<li>Make easy payments without making errors</li>
									<li>Check for Identity Theft</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row img-panel">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="hidden-xs hidden-sm col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1"><img src="<?= $assetBundle->baseUrl ?>/images/business_icon03.png"></div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Businesses Maintain Steady Cashflow</h3>
							</div>
							<div class="panel-body">
								<ul>
									<li>Load invoicing information quick and easy</li>
									<li>Monitor payments as and when they happen</li>
									<li>We make use of an accredited payments gateway</li>
									<li>Maintain your cash flow position</li>
									<li>100% paperless and environmentally friendly</li>
									<li>100% reduction in reconciliation errors</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row img-panel">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="hidden-xs hidden-sm col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1"><img src="<?= $assetBundle->baseUrl ?>/images/business_icon01.png"></div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Accountants Improve Debtor Management</h3>
							</div>
							<div class="panel-body">
								<ul>
									<li>Customers spend time on selling more</li>
									<li>Reduce customer administrative overheads by 30%</li>
									<li>Extend your accounting capability</li>
									<li>Improve your invoicing and GL processes</li>
									<li>Improve your customer billing experience</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row img-panel">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="hidden-xs hidden-sm col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1"><img src="<?= $assetBundle->baseUrl ?>/images/business_icon02.png"></div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Factor/Financiers Gain Access to New Opportunities</h3>
							</div>
							<div class="panel-body">
								<ul>
									<li>Identify qualified business partners</li>
									<li>Reduce administrative overheads by 50%</li>
									<li>Immediate notifications on available books and invoices</li>
									<li>Invoice factoring exchange</li>
									<li>Support businesses cash flow</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="panel panel-default" style="background: #d9edf7; border: none">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
						<h3><img style="width:48px;" src="<?= $assetBundle->baseUrl ?>/images/icon-only_billsource_greenl.png"> "Manage all your bills in one place"  <button type="button" id="myButton" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
								<a href="<?= Url::to(['account/signup']) ?>" class="signup-link">Sign up NOW</a>
						</button></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="centered" style="padding-left: 4px;">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="thumbnail">
						<img alt="BillSource - EBPP" src="<?= $assetBundle->baseUrl ?>/images/ebpp.png">
						<div class="caption text-center">
							<p>We are a bill aggregator offering you the ability to make payments</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="thumbnail">
						<img alt="BillSource - cloud" src="<?= $assetBundle->baseUrl ?>/images/cloud.png">
						<div class="caption text-center">
							<p>We manage your platforms through a subscription based SaaS model</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="thumbnail">
						<img alt="BillSource - CRM" src="<?= $assetBundle->baseUrl ?>/images/crm.png">
						<div class="caption text-center">
							<p>We manage your platforms through a subscription based SaaS model</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="thumbnail">
						<img alt="BillSource - API" src="<?= $assetBundle->baseUrl ?>/images/api.png">
						<div class="caption text-center">
							<p>We offer seamless integration with your existing systems</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="thumbnail">
						<img alt="BillSource - secure" src="<?= $assetBundle->baseUrl ?>/images/secure(1).png">
						<div class="caption text-center">
							<p>Security of our customers’ data is our top priority</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="thumbnail">
						<img alt="BillSource - vault" src="<?= $assetBundle->baseUrl ?>/images/vault.png">
						<div class="caption text-center">
							<p>We offer a simple and secure document management vault</p>
						</div>
					</div>
				</div>
			</div>
		</div><!--/Product boxes (badges) images-->
		<div class="clear"></div>
		<div class="row" style="margin-bottom:20px;">
			<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-2 col-sm-2 col-md-2 col-lg-2"><img src="<?= $assetBundle->baseUrl ?>/images/icon_za.png"></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><img src="<?= $assetBundle->baseUrl ?>/images/icon_nga.png"></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><img src="<?= $assetBundle->baseUrl ?>/images/icon_gb.png"></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><img src="<?= $assetBundle->baseUrl ?>/images/icon_us.png"></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><img src="<?= $assetBundle->baseUrl ?>/images/icon_aud.png"></div>
		</div>
		<div class="panel panel-default" style="background: #d9edf7; border: none">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
						<h3><img style="width:48px;" src="<?= $assetBundle->baseUrl ?>/images/icon-only_billsource_teal.png"> "Billsource is <span style="color:#EEAA44;">used by many</span> to weather the storm"  <button type="button" id="myButton" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
								<a href="<?= Url::to(['account/signup']) ?>" class="signup-link">Sign up NOW</a>
						</button></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
				<div class="thumbnail">
					<img class="img-responsive hidden-xs" src="<?= $assetBundle->baseUrl ?>/images/mark.JPG">
					<div class="caption quote">
						<h3>Mark Williams,<br>
						<small>Professional Athlete, Sydney</small></h3>
						<p></p>
						<h4>"</h4>I can see all my bills in one place. It helps me to stay credit worthy and never miss a payment. Thanks
						<h4>"</h4>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
				<div class="thumbnail">
					<img class="img-responsive hidden-xs" src="<?= $assetBundle->baseUrl ?>/images/kenneth.jpg">
					<div class="caption quote">
						<h3>Kenneth Onah,<br>
						<small>SME Owner, Lagos, Nigeria</small></h3>
						<p></p>
						<h4>"</h4>Since we"ve moved to cloud billing and collections, our cash flow have improved. BillSource breaks down barriers between debtors and creditors
						<h4>"</h4>

					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
				<div class="thumbnail">
					<img class="img-responsive hidden-xs" src="<?= $assetBundle->baseUrl ?>/images/sarah.JPG">
					<div class="caption quote">
						<h3>Sarah Conner,<br>
						<small>Accountant, Chicago, Illinois</small></h3>
						<p></p>
						<h4>"</h4>We extended our services to our clients and have received positive feedback helping them make money move faster. In the process we have added additional income streams and won new business. The DCA programme works well in our business. Great.
						<h4>"</h4>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
				<div class="thumbnail">
					<img class="img-responsive hidden-xs" src="<?= $assetBundle->baseUrl ?>/images/ceo.jpg">
					<div class="caption quote">
						<h3>CEO,<br>
						<small>Debt Console, Durban, South Africa</small></h3>
						<p></p>
						<h4>"</h4>We are helping individuals struggling with debt. BillSource makes it possible for us to reach new customers daily
						<h4>"</h4>
						<p></p>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="panel panel-default" style="background: #d9edf7; border: none">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
						<h3><img style="width:48px;" src="<?= $assetBundle->baseUrl ?>/images/icon-only_billsource_blue.png"> "Billsource is <span style="color:#EEAA44;">tailored</span> to your specific business <span style="color:#EEAA44;">needs</span>"  <button type="button" id="myButton" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
								<a href="<?= Url::to(['account/signup']) ?>" class="signup-link">Sign up NOW</a>
						</button></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-bordered centered table-condensed table-striped">
					<thead>
						<tr>
							<th><b>Business size</b></th>
							<th><b>Startup</b></th>
							<th><b>Small</b></th>
							<th><b>Medium</b></th>
							<th><b>Agency</b></th>
							<th><b>Factor</b></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td>$5.95</td>
							<td>Basic $15.95</td>
							<td>Select $150.95</td>
							<td>Prime $200.95</td>
							<td>Prime $250.95</td>
						</tr>
						<tr>
							<td>No. Customers</td>
							<td>Up to 1,000</td>
							<td>Up to 100,000</td>
							<td>Up to 1,500,000</td>
							<td>Up to 3,500,000</td>
							<td>Up to 5,500,000</td>
						</tr>
						<tr>
							<td>Bill Presentment and Payment</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Invoice Age Tracking</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>CRM and Product Catalogue</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Email and SMS Notifies</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>TLS Security and Backups</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Business Support</td>
							<td></td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Statements and Reports</td>
							<td></td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Multiple Users</td>
							<td></td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Bulk Imports and APIs</td>
							<td></td>
							<td></td>
							<td>&#10004;</td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Agency Billing</td>
							<td></td>
							<td></td>
							<td></td>
							<td>&#10004;</td>
							<td>&#10004;</td>
						</tr>
						<tr>
							<td>Factoring Exchange</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>&#10004;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="clear"></div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><img class="img-responsive img-rounded" src="<?= $assetBundle->baseUrl ?>/images/BNR_lp-home_03.jpg"></div>
				</div>
			</div>
		</div>
	</div>
</div>