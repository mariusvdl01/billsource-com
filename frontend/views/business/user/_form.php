<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\business\models\BusinessClientUser */
/* @var $form yii\widgets\ActiveForm */

$roles = [
	'reader' => 'Read Access',
	'loader' => 'Manager',
	'singleUserAdmin' => 'System Admin',
]
?>

<div class="business-client-user-form">
	<br />
	<div class="row">
		<div class="col-sm-12">
		    <?php $form = ActiveForm::begin([
		    	'id' => 'form-create-signup',
		    	'layout' => 'horizontal',
		    	'fieldConfig' => [
		    		'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-offset-3 col-sm-8\">{error}</div>",
		    		'labelOptions' => ['class' => 'col-sm-3 control-label'],
		    	],
		    ]); ?>

			<?= $form->field($model, 'full_name')->textInput() ?>

		    <?= $form->field($model, 'email')->textInput() ?>
		
		    <?= $form->field($model, 'password')->passwordInput() ?>
		
		    <?= $form->field($model, 'role')->dropDownList($roles) ?>
		
			<?= $form->field($model, 'active')->checkbox() ?>
			<div class="col-sm-offset-3 col-sm-9">
			    <div class="col-sm-9">
		        	<?= Html::submitButton('Submit', [
		            	'class' => 'btn btn-primary', 
		            	'name' => 'create-user',
					]) ?>
				
		        	<?= Html::resetButton('Clear', [
		            	'class' => 'btn btn-primary', 
					]) ?>
				
		        	<?= Html::a('Cancel', '/business/user', [
		            	'class' => 'btn btn-default', 
					]) ?>
				</div>
			</div>
		    <?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
