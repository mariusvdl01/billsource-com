<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\IndividualAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $financial frontend\models\individual\IndividualFinancial */

IndividualAsset::register($this);
$this->title = 'Financial Position';
$position = 0;
$ratio = 0;
if( isset($data['total_liabilities']) && $data['total_liabilities'] != 0 )
{
	$position = $data['total_assets'] - $data['total_liabilities'];
	$ratio = $data['total_liabilities'] / $data['total_liabilities'];
}
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="individual-financial">
			<h3>Financial Position</h3>
		   	<div class="row">
		   		<div class="col-lg-12">
		        	<?php $form = ActiveForm::begin([
							'id' => 'individual-financial-form',
							'layout' => 'horizontal' 
					]);?>
						<br />
							<ul class="nav nav-tabs" role="tablist">
		    				<li role="presentation" class="active">
		    					<a href="#income" aria-controls="income" role="tab" data-toggle="tab">Income</a>
		    				</li>
		    				 <li role="presentation">
		    				 	<a href="#assets" aria-controls="assets" role="tab" data-toggle="tab">Assets</a>
		    				 </li>
		    				 <li role="presentation">
		    				 	<a href="#liabilities" aria-controls="liabilities" role="tab" data-toggle="tab">Liabilities</a>
		    				 </li>
		    			</ul>
		    			<div class="tab-content">
		    				<br />
		    				<div role="tabpanel" class="tab-pane fade in active" id="income">
				                <?= $form->field($financial, 'gross_income')->label('Monthly Gross Income') ?>
				                <?= $form->field($financial, 'net_income')->label('Monthly Net Income (paid into account)') ?>
				                <?= $form->field($financial, 'total_expenses')->label('Total Monthly Expenses') ?>
				                <?= $form->field($financial, 'surplus')->textInput([
				                	'readonly' => true,
				                ])->hint('You cannot edit this field')->label('Surplus Monthly Income') ?>
								</div>
			          	<div role="tabpanel" class="tab-pane fade" id="assets">
				                <?= $form->field($financial, 'home_1')->label('First Home') ?>
				                <?= $form->field($financial, 'home_2')->label('Second Home') ?>
				                <?= $form->field($financial, 'home_3')->label('Third Home') ?>
				                <?= $form->field($financial, 'vehicle_1')->label('First Car') ?>
				                <?= $form->field($financial, 'vehicle_2')->label('Second Car') ?>
				                <?= $form->field($financial, 'craft')->label('Leisure Craft') ?>
				                <?= $form->field($financial, 'insurance')->label('Leisure Craft Finance') ?>
				                <?= $form->field($financial, 'investments')->label('Portfolio Investments') ?>
				                <?= $form->field($financial, 'savings')->label('Savings (Cash, Deposits)') ?>
				                <?= $form->field($financial, 'total_assets')->textInput([
				                	'readonly' => true,
				                ])->hint('You cannot edit this field')->label('Total Assets') ?>
							</div>
			        	<div role="tabpanel" class="tab-pane fade" id="liabilities">
				                <?= $form->field($financial, 'bond_1')->label('Bond 1') ?>
				                <?= $form->field($financial, 'bond_2')->label('Bond 2') ?>
				                <?= $form->field($financial, 'bond_3')->label('Bond 3') ?>
				                <?= $form->field($financial, 'car_loan_1')->label('Vehicle Finance 1') ?>
				                <?= $form->field($financial, 'car_loan_2')->label('Vehicle Finance 2') ?>
				                <?= $form->field($financial, 'craft_loan')->label('Leisure Craft Finance') ?>
				                <?= $form->field($financial, 'debt')->label('Short Term Debt (Personal Loan)') ?>
				                <?= $form->field($financial, 'outstanding_bills')->textInput([
				                	'readonly' => true,
				                ])->hint('You cannot edit this field')->label('Outstanding Bills') ?>
				                <?= $form->field($financial, 'total_liabilities')->textInput([
				                	'readonly' => true,
				                ])->hint('You cannot edit this field')->label('Your Total Liabilities') ?>
							</div>
			        </div>  
		          <div class="form-group">
		          	<div class="col-sm-offset-9 col-sm-3">
			          	<?= Html::submitButton('Save', [
			            	'class' => 'btn btn-primary', 
								])?>
								</div>
		          </div>
		         <?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>