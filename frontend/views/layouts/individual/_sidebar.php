<?php 
$billsrcAsset = $this->params['billsourceAsset']
?>
<!-- start of #side_bar -->
<div data-spy="affix" id="side_bar" style="position:static;z-index:10000;">
	<h2>
		<br />Need Help?
	</h2>
	<div id="modals">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".profile">My Profile</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".financial">My Financial Position</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".outstanding">My Outstanding Bills</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".assistance">My Assistance</button>
	</div>
	<div class="modal fade profile" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="loginModalLabel">My Profile</h4>
				</div>
				<div class="modal-body">
				<p>This is your contact and profile details as you have supplied and saved when you registered. 
                	If you want to update the details visit 'My Profile' and make the changes to reflect your current up to date details.</p>
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
	<div class="modal fade financial" tabindex="-1" role="dialog" aria-labelledby="resetLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="resetLabel">My Financial Position</h4>
				</div>
				<div class="modal-body">
					<p>You financial position is a summary of your total assets, total liabilities which include your outstanding bill total,
                	 the equity in your portfolio, as well as the ratio between assets and liabilities expressed as percentage.</p>
                <p>Visit the 'My Financial Position' to capture and maintain your current assets and liabilities regularly to calculate 
                	and monitor your an up to date debt ratio.</p>
                <p>Regularly visit and update your position. If for example you need a loan or want to purchase a car, 
                	see how your debt ratio changes.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade outstanding" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="infoLabel">My Outstanding Bills</h4>
				</div>
				<div class="modal-body">
					<p>You will notice that if you have any outstanding or long overdue bills, they will be listed from oldest to latest. 
                	You will also see the total outstanding amount which is the sum of all your outstanding bills.</p>
                <p>These bills are collected from various suppliers you have done business with. BillSource will search 
                	against your e-mail address, mobile number or ID to locate and present to you.</p>
                <p>Regularly visit this page to see if you have new dues. This way you are informed and prevent from being listed.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade assistance" tabindex="-1" role="dialog" aria-labelledby="priceLabel" aria-hidden="true">
		<div class="modal-dialog modal-m">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="priceLabel">My Assistance</h4>
				</div>
				<div class="modal-body">
					<p>At some point some of are overwhelmed by bad decisions and we are unable to see a way out. 
                	When and only when you reach a point of not being able to service your debt, you can reach 
                    out to our network of laywers that specialise in debt counselling and help you structure your income and expenses.</p>
                <p>Once you have decided to take this route and submitted your details it will lock the ability to do 
                	this again – the counsellors will then make contact with you prior to your request for assistance. </p>
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