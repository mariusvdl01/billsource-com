<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\SignupForm */

$this->title = 'Billsource - Signup';
//Yii::$app->params['assetBundle']->registerAssetFiles($this);
$data = [
	'2' => 'Biller',
	'3' => 'Individual',
	'4' => 'Agency',
	//'5' => 'VAS',
	//'6' => 'Counsellor',
	'7' => 'Factor/FSP'
];
$terms = Html::a(Yii::t('app', 'Terms and Conditions'), ['#'], [
	'data-toggle' => 'modal', 
	'data-target' => '.terms'
])

?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="default-signup">
			<h4 class="text-center"><?= Html::encode($this->title) ?></h4>
			<p class="text-center">Please fill out the fields below</p>
			<br />
			<div class="row">
				<div class="col-sm-12">
		            <?php $form = ActiveForm::begin([
		            	'id' => 'form-signup',
		            	'fieldConfig' => [
		            		'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-offset-3 col-sm-8\">{error}</div>",
		            		'labelOptions' => ['class' => 'col-sm-3 control-label'],
		            	],
		            ]); ?>
		            	<?= $form->field($model, 'firstname')->label('First name') ?>
		            	<?= $form->field($model, 'lastname')->label('Last name') ?>
		                <?= $form->field($model, 'email') ?>
		                <?= $form->field($model, 'password')->passwordInput() ?>
		                <?= $form->field($model, 'confirmPassword')->passwordInput()->label('Confirm Password') ?>
			            <?= $form->field($model, 'category')->radioList($data, [
			            	'inline' => true,
							'unselect' => null,
							'class' => 'btn-group-sm',
						]); ?>
						<?= $form->field($model, 'tcs')->checkbox(['label' => 'By Clicking Submit you agree to Billsource ' . $terms, 'uncheck' => null])->label('') ?>
			            <div class="col-sm-offset-3 col-sm-3">
			            	<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
						</div>
		            <?php ActiveForm::end(); ?>
		        </div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade terms" tabindex="-1" role="dialog" aria-labelledby="terms" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="termsLabel">Billsource Terms and Conditions</h4>
				</div>
				<div class="modal-body">
					<p>[Updated: 01/05/2012]<br \>
					This Terms of Service governs your use of our website; by using our website, you accept this disclaimer in full. If you disagree with any part of this Terms of Serivce, do not use our website.</p>
					<p><strong>Terms of Service</strong><br \>
					By using BillSource ("The Service"), you are agreeing to be bound by the following terms and conditions ("Terms of Service").<br \>
					Mobyl Business Systems reserves the right to update and change the Terms of Service from time to time without notice. Any new features that augment or enhance the current Service, including the release of new tools and resources, shall be subject to the Terms of Service. Continued use of the Service after any such changes shall constitute your consent to such changes. You can review the most current version of the Terms of Service at any time at: http://www.BillSource.com/terms/<br \>
					1. You must be 18 years or older to use this Service.<br \>
					2. You must be human. “Bots” or other automated sign-ups aren't permitted.<br \>
					3. You must provide your legal full name, a valid email address, and any other information requested in order to complete the signup process.<br \>
					4. You're responsible for maintaining the security of your account and password. Mobyl Business Systems can't and won't be liable for any loss or damage from your failure to comply with this security obligation.<br \>
					5. One person or legal entity may not maintain more than one free account.<br \>
					6. You may not use the Service for any illegal or unauthorized purpose. You must not, in the use of the Service, violate any laws in your jurisdiction (including but not limited to copyright laws).<br \>
					Violation of any of these agreements will result in the termination of your Account. While Mobyl Business Systems prohibits such conduct and Content on the Service, you understand and agree that Mobyl Business Systems can't be responsible for the Content posted on the Service and you nonetheless may be exposed to such materials. You agree to use the Service at your own risk.</p>
					<p><strong>Cancellation and Termination</strong><br />
					1. You are solely responsible for properly cancelling your account. An email or phone request to cancel your account will be considered cancellation. You can cancel your account at any time. All of your Content will be immediately deleted from the Service upon cancellation.<br \>
					2. Mobyl Business Systems, in its sole discretion, has the right to suspend or terminate your account and refuse any and all current or future use of the Service, or any other Mobyl Business Systems service, for any reason at any time. Such termination of the Service will result in the deactivation or deletion of your Account or your access to your Account, and the forfeiture and relinquishment of all Content in your Account. Mobyl Business Systems reserves the right to refuse service to anyone for any reason at any time.</p>
					<p><strong>Modifications to the Service and Prices</strong><br \>
					1. Mobyl Business Systems reserves the right at any time and from time to time to modify or discontinue, temporarily or permanently, the Service (or any part thereof) with or without notice.<br \>
					2. Prices of all Services, including but not limited to monthly subscription plan fees to the Service, are subject to change upon 30 days notice. Such notice may be provided at any time by posting the changes to the BillSource website (www.BillSource.com) or the Service itself.<br \>
					3. Mobyl Business Systems shall not be liable to you or to any third party for any modification, price change, suspension or discontinuance of the Service.</p>
					<p><strong>Copyright and Content Ownership</strong><br \>
					1. Mobyl Business Systems claims no intellectual property rights over the material you provide to the Service. However, by setting your pages to be shared publicly, you agree to allow others to view and share your Content.<br \>
					2.Mobyl Business Systems does not pre-screen Content, but Mobyl Business Systems and its designee have the right (but not the obligation) in their sole discretion to refuse or remove any Content that is available via the Service.</p>
					<p><strong>General Conditions</strong><br \>
					1. Your use of the Service is at your sole risk. The service is provided on an “as is” and “as available” basis.<br \>
					2. Technical support is provided to all account holders, but is only available via email.<br \>
					3. You must not modify, adapt or hack the Service or modify another website so as to falsely imply that it is associated with the Service, Mobyl Business Systems, or any other Mobyl Business Systems service.<br \>
					4. You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the Service, use of the Service, or access to the Service without the express written permission by Mobyl Business Systems.<br \>
					5. Mobyl Business Systems may, but has no obligation to, remove Content and Accounts containing Content that it determines in its sole discretion is unlawful, offensive, threatening, libelous, defamatory, pornographic, obscene or otherwise objectionable or violates any party’s intellectual property or these Terms of Service.<br \>
					6. Verbal, physical, written or other abuse (including threats of abuse or retribution) of any Mobyl Business Systems customer, employee, member, or officer will result in immediate account termination.<br \>
					7. You understand that the technical processing and transmission of the Service, including your Content, may be transfered unencrypted and involve (a) transmissions over various networks; and (b) changes to conform and adapt to technical requirements of connecting networks or devices.<br \>
					8. You must not upload, post, host, or transmit unsolicited or “spam” messages.<br \>
					9. You must not transmit any worms or viruses or any code of a destructive nature.<br \>
					10. Mobyl Business Systems does not warrant that (i) the service will meet your specific requirements, (ii) the service will be uninterrupted, timely, secure, or error-free, (iii) the results that may be obtained from the use of the service will be accurate or reliable, (iv) the quality of any products, services, information, or other material purchased or obtained by you through the service will meet your expectations, and (v) any errors in the Service will be corrected.<br \>
					11. You expressly understand and agree that Mobyl Business Systems shall not be liable for any direct, indirect, incidental, special, consequential or exemplary damages, including but not limited to, damages for loss of profits, goodwill, use, data or other intangible losses (even if Mobyl Business Systems has been advised of the possibility of such damages), resulting from: (i) the use or the inability to use the service; (ii) the cost of procurement of substitute goods and services resulting from any goods, data, information or services purchased or obtained or messages received or transactions entered into through or from the service; (iii) unauthorized access to or alteration of your transmissions or data; (iv) statements or conduct of any third party on the service; or (v) any other matter relating to the service.<br \>
					12. The failure of Mobyl Business Systems to exercise or enforce any right or provision of the Terms of Service shall not constitute a waiver of such right or provision. The Terms of Service constitutes the entire agreement between you and Mobyl Business Systems and govern your use of the Service, superceding any prior agreements between you and Mobyl Business Systems (including, but not limited to, any prior versions of the Terms of Service).<br \>
					13. Questions about the Terms of Service should be sent to contact at BillSource.co.za .</p>
					<p><strong>Third party websites</strong><br \>
					1. The website contains links to other websites. We are not responsible for the content of third party websites.</p>
					<p><strong>Variation</strong><br />
					1. We may revise this Terms of Service from time-to-time. Regularly check this page to ensure you are familiar with the latest and current version.</p>
					<p><strong>Entire agreement</strong><br \>
					1. This Terms of Service constitutes the entire agreement between you and us in relation to your use of our website, and supersedes all previous agreements in respect of your use of this website.</p>
					<p><strong>Using your personal data</strong><br />
					Personal data submitted on this website will be used for the purposes specified in this privacy policy or in relevant parts of the website.<br \>
					In addition to the uses identified elsewhere in this privacy policy, we may use your personal information to:<br \>
					1. Enhance your experience using the sets of services;<br \>
					2. Post or send information (other than marketing communications) to you which we think may be of interest to you by post or by email or similar technology;<br \>
					3. Send to you marketing communications relating to our business [or the businesses involved] which is of interest to you by post or, where you have specifically agreed to this, by email or similar technology (you can inform us at any time if you no longer require marketing communications to be sent by Emailing us.<br \>
					4. Provide other companies with statistical information about our users - but this information will not be used to identify any individual user. We will not without your express consent provide your personal information to any third parties for the purpose of direct marketing.</p>
					<p><strong>Other disclosures</strong><br />
					In addition to the disclosures reasonably necessary for the purposes identified elsewhere in this privacy policy, we may disclose information about you:<br \>
					1. to the extent that we are required to do so by law;<br \>
					2. in connection with any legal proceedings or prospective legal proceedings;<br \>
					3. in order to establish, exercise or defend our legal rights (including providing information to others for the purposes of fraud prevention and reducing credit risk); and<br \>
					4. to the purchaser or seller (or prospective purchaser or seller) of any business or asset which we are (or are contemplating) selling or purchasing.<br \>
					Except as provided in this privacy policy, we will not provide your information to third parties.</p>
					<p><strong>Security of your personal data</strong><br \>
					We will take all reasonable precautions to prevent the loss, misuse or alteration of your personal information. 
					Of course, data transmission over the internet is inherently insecure, and we cannot guarantee the security of 
					data sent over the internet. We intently limit data transfer as it is critical to our competitive advantage 
					therefor further keep and maintain your interest to your personal data.</p>
					<p><strong>Indemnification</strong><br \>
					Except for the indemnification obligations of the parties set out herein, to the fullest extent permitted by applicable law, under no circumstances shall either party be liable to the other party 
					on account of any claim (whether based upon principles of contract, warranty, negligence or other tort, breach of any statutory duty, the failure of any limited remedy to achieve its essential purpose, 
					or otherwise) for any special, consequential, incidental, or exemplary damages, including but not limited to lost profits, even if a party has been advised of the possibility of such damages. 
					Except for the indemnification and confidentiality obligations of the parties set out herein, in no event shall either party's liability exceed an amount equal to the fees paid by licensee under 
					this agreement. If you are dissatisfied with the software your sole and exclusive remedy is to discontinue use of the software.</p>
				</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>