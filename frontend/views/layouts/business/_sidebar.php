<?php

use common\models\business\BusinessClient as Client;

$billsrcAsset = $this->params['billsourceAsset'];
$secure = Yii::$app->request->isSecureConnection;
$dca = isset(Yii::$app->params['client']->type) ? Yii::$app->params['client']->type == Client::CATEGORY_DCA : false;

?>
<!-- start of #side_bar -->
<div data-spy="affix" id="side_bar" style="position:static;z-index:10000;">
	<?php if(('businessAdmin'==$this->params['role'] && $dca)
		|| !empty(Yii::$app->session['user.idbeforeswitch'])) : ?>
		<?= \kartik\select2\Select2::widget([
			'name' => 'billers-select',
			'value' => Yii::$app->getUser()->getId(),
			'data' => Yii::$app->params['data'],
			'options' => [
				'id' => 'billers-select'
			]
		]) ?>
		
	<?php endif; ?>
	<h2>
		<br />Need Help?
	</h2>
	<div id="modals">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".profile">My Profile</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".priceModal">Price List</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".invoice">New Invoice</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".debtors">My Debtors</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".creditors">My Creditors</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".vetting">Vetting</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".contact">Contact</button>
	</div>
	<div class="modal fade profile" tabindex="-1" role="dialog" aria-labelledby="profileLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="profileLabel">My Profile</h4>
				</div>
				<div class="modal-body">
				<p>This is your contact and profile details as you have supplied and saved when you registered. 
                    	If you want to update the details visit 'My Profile' and make the changes to reflect you current up to date details.</p>
                    <p>Your details will then be updated in the summary fields.</p>
                    <p>Remember that the more complete your profile, the more accurate the search for outstanding or long overdue bills
                    	 – the first step in preventing you are handed over or listed with credit bureaus affecting your credit worthiness.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade priceModal" tabindex="-1" role="dialog" aria-labelledby="priceLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="priceLabel">Price List</h4>
				</div>
				<div class="modal-body">
					<iframe src="<?= Yii::$app->request->getHostInfo() ?>/_price-list.php" width="800" height="1000"></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade invoice" tabindex="-1" role="dialog" aria-labelledby="resetLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="resetLabel">New Invoice</h4>
				</div>
				<div class="modal-body">
					<p>Allow you to capture or import invoice(s)</p>
                    <p>Tab through all the invoice fields to capture all required fields.
                    Optional field may be left blank</p>
                    <p>On the invoice line detail. The first line is required.
                    Tab and populate all lines that is required by the invoice up to 4 line
                    Hit add to insert the new invoice</p>
                    <p>Alternatively select a csv file with all the field then hit add.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade debtors" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="infoLabel">My Debtors</h4>
				</div>
				<div class="modal-body">
					<p>List of all invoice that was previously loaded.</p>
                    <p>To permanantly remove the invoice click on the delete link.
                    Then click on the confirm delete for the invoice to be removed.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade creditors" tabindex="-1" role="dialog" aria-labelledby="priceLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="priceLabel">My Creditors</h4>
				</div>
				<div class="modal-body">
					A list of all invoice that is loaded against our business.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade vetting" tabindex="-1" role="dialog" aria-labelledby="priceLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="priceLabel">Vetting</h4>
				</div>
				<div class="modal-body">
					<p>Find out what the outstanding amount for a business
                    with a specifice reference.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade contact" tabindex="-1" role="dialog" aria-labelledby="priceLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="priceLabel">Contact</h4>
				</div>
				<div class="modal-body">
					<p>A contact form for sending billsource an email and
                    other contact details</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div id="social" class="center">
		<ul>
			<li><a href="https://www.facebook.com/BillSource" target="_blank"><img src="<?= $billsrcAsset->baseUrl ?>/images/facebook.png" alt="facebook"></a></li>
			<li><a href="http://www.linkedin.com/company/billsource" target="_blank"><img src="<?= $billsrcAsset->baseUrl ?>/images/linkedin.png" alt="linkedin"></a></li>
			<li><a href="https://plus.google.com/113972576532960342845/" target="_blank"><img src="<?= $billsrcAsset->baseUrl ?>/images/googleplus.png" alt="google+"></a></li>
			<li><a href="https://twitter.com/allyourbills" target="_blank"><img src="<?= $billsrcAsset->baseUrl ?>/images/twitter.png" alt="twitter"></a></li>
			<li><a href="https://www.pinterest.com/billsource/" target="_blank"><img src="<?= $billsrcAsset->baseUrl ?>/images/pinterest.png" alt="pinterest"></a></li>
		</ul>
	</div>
	<div class="clear"></div>
	<?= $this->render('//layouts/_adverts') ?>
</div>