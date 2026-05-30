<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\assets\IndividualAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

$this->title = 'Billsource - Request assistance';
$assetBundle = Yii::$app->params['assetBundle'];
IndividualAsset::register($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="individual-assistance">
			<br />
			<div class="row">
				<div class="col-sm-12">
				<?php if($complete) : ?>
				<h4 class="text-center">Please check all fields to request for loan or assistance:</h4>
		            <?php $form = ActiveForm::begin([
		            		'id' => 'form-signup',
		            		'layout' => 'horizontal',
		            ]); ?>
		            <?php if(isset($tab) && $tab == '6'): ?>
		            	<?= $form->field($model, 'submit_assistance')->checkbox()
		            	->label('I choose to submit my details for debt counseling') ?>
		            <?php else: ?>
		            	<?= $form->field($model, 'submit_assistance')->checkbox()
		            	->label('I choose to submit my details to obtain a loan') ?>
		            <?php endif; ?>
		            
		            	<?= $form->field($model, 'assistance_agree_terms')->checkbox()
		            	->label('I agree to terms and conditions') ?>
		            	
		                <?= $form->field($model, 'assistance_update')->checkbox()
		                ->label('I confirm my profile is up to date') ?>
		                
					<?php if(isset($tab) && $tab == '6'): ?>
		                <?= $form->field($model, 'assistance_contact')->checkbox()
		                ->label('I agree to have a debt counseller contact me') ?>
		            <?php else: ?>
		            	<?= $form->field($model, 'assistance_contact')->checkbox()
		                ->label('I agree to have a Financial Service Provider contact me') ?>
		            <?php endif; ?>
		                <div class="form-group">
			                <div class="col-sm-offset-3 col-sm-6">
			                <?php if(isset($tab) && $tab == '6'): ?>
			                    <?= Html::submitButton('Request assistance', [
			            				'class' => 'btn btn-primary', 
			            				'name' => 'counselling',
			                    		'value' => 'Request assistance',
								]) ?>
							<?php else: ?>
			            		<?= Html::submitButton('Get a loan', [
			            				'class' => 'btn btn-primary', 
			            				'name' => 'loan',
			            				'value' => 'Get a loan'
								]) ?>
			            	<?php endif; ?>
							</div>
		                </div>
		            <?php ActiveForm::end(); ?>
				<?php else : ?>
					<div class="alert alert-info">
					<p>To request a loan or assistance: address details, mobile and ID number must be provided.</p>
					<p><?= Html::a('Edit profile', Url::to('update'), [
						'class' => 'btn btn-primary',
						'style' => 'color:#ececec',
					])?></p>
				</div>
				<?php endif; ?>
		        </div>
			</div>
		</div>
	</div>
</div>