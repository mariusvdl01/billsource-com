<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\ContactForm */

$tel = Yii::$app->params['contactTel'];
$mobileSales = Yii::$app->params['contactSales'];
$sales = Yii::$app->params['salesEmail'];
$support = Yii::$app->params['supportEmail'];
$technical = Yii::$app->params['contactTechnical'];

$this->title = 'Billsource - Contact';
$tickets = [
	'Log change request' 					=> 'Log change request',
	'Log a support ticket' 					=> 'Log a support ticket',
	'Debt counselling' 						=> 'Debt counselling',
	'Loan application' 						=> 'Loan application',
	'Rewards program' 						=> 'Rewards program',
	'Making payments' 						=> 'Making payments',
	'Disputes' 								=> 'Disputes',
	'Business Process Outsourcing (BPO)' 	=> 'Business Process Outsourcing (BPO)',
	'Value Added Service (VAS)' 			=> 'Value Added Service (VAS)',
	'Business user' 						=> 'Business user',
	'Personal user' 						=> 'Personal user',
	'Suggestions' 							=> 'Suggestions',
	'Others' 								=> 'Others'
];

?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="default-contact">

		    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')) : ?>
			    <div class="alert alert-success">Thank you for contacting us. We will respond to you as soon as possible.</div>
				<div class="alert alert-info">
					<p>For urgent response contact one of the numbers below.</p>
					<p><strong>Technical Support: </strong><?= $support ?></p>
					<p><strong>Sales and Administration: </strong><?= $sales ?></p>
					<p><strong>Telephone (Info): </strong><?= $tel ?></p>
					<p><strong>Telephone (Sales): </strong><?= $mobileSales ?><br /></p>
					<p><strong>Telephone (Technical): </strong><?= $technical ?><br /></p>
					<p>Follow us on Twitter @AllYourBills</p>
				</div>
		    <?php else : ?>
			        <div id="sky_block">
						<h2>Contact us</h2>
						<p>Billsource offers a range of products and services aimed to address
							a number of challenges regarding managing debt and aim to make
							changes that matter to you.</p>
					</div>
			<!-- end of #sky_block -->
		            <?php	
						$form = ActiveForm::begin ([ 
							'id' => 'contact-form',
							'layout' => 'horizontal',
							'fieldConfig' => [
								'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
								'horizontalCssClasses' => [
									'label' => 'col-sm-3',
									'offset' => '',
									'wrapper' => 'col-sm-8',
									'error' => '',
									'hint' => '',
								],
							],
						]);
					?>
		                <?= $form->field($model, 'name')->label('From')->textInput([
		                	'value' => isset($from) ? $from : ''
		                ]) ?>
		                <?= $form->field($model, 'email')->textInput([
		                	'value' => isset($email) ? $email : ''
		                ]) ?>
		                <?= $form->field($model, 'subject')->dropdownList($tickets)->label('Regarding') ?>
		                <?= $form->field($model, 'body')->textArea(['rows' => 6])->label('Message') ?>
		                <?= $form->field($model, 'verifyCode')->widget (Captcha::className (), [ 
		                		'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
		                		'captchaAction' => 'default/captcha' 
		                ]) ?>
		                <div class="form-group">
		                	<div class="col-sm-offset-3 col-sm-7">
			                	<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
							</div>
		                </div>
		            <?php ActiveForm::end(); ?>
		    <?php endif; ?>
		</div>
	</div>
</div>
