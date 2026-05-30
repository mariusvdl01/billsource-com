<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $model frontend\models\individual\IndividualReading */
/* @var $form yii\widgets\ActiveForm */

?>
<?php $form = ActiveForm::begin([
	'type' => ActiveForm::TYPE_HORIZONTAL,
	'formConfig' => [
		'labelSpan'=>3,
		'deviceSize' => ActiveForm::SIZE_SMALL
	],
]); ?>
	<div class="form-group kv-fieldset-inline">
		<?= Html::activeLabel($model, 'entity_id', [
	        'label'=>'Utility', 
	        'class'=>'col-sm-2 control-label'
	    ]); ?>
	    	<div class="col-sm-4">
				<?= Select2::widget([
					'name' => 'utility',
					'model'=> $read,
					'data' => $reading,
					'options' => [
				    	'placeholder' => 'Select utility...',
				    	//'multiple' => true
				    ],
				    'pluginOptions' => [
				    	'allowClear' => true
				    ],
				])?>
			</div>
		<?= Html::activeLabel($model, 'entity_id', [
	        'label'=>'Meter Reading (Prev.)', 
	        'class'=>'col-sm-3 control-label'
	    ]); ?>
			<div class="col-sm-3">
		    	<?= $form->field($model, 'reading_previous', [
		    			'showLabels'=>false
		    	])->textInput(['maxlength' => true]) ?>
			</div>
		<?= Html::activeLabel($model, 'entity_id', [
	        'label'=>'Meter Reading (Cur.)', 
	        'class'=>'col-sm-3 control-label'
	    ]); ?>
			<div class="col-sm-3">
		    	<?= $form->field($model, 'reading_current', [
		    			'showLabels'=>false
		    	])->textInput(['maxlength' => true]) ?>
			</div>
		<?= Html::activeLabel($model, 'entity_id', [
	        'label'=>'Rate', 
	        'class'=>'col-sm-2 control-label'
	    ]); ?>
	    	<div class="col-sm-4">
		    	<?= TouchSpin::widget([
		    			'name'=>'rate',
		    			'options' => ['placeholder' => 'Adjust Rate'],
		    			'pluginOptions' => [
		    				'min'=>1,
		    				'max'=>100,
		    				'step'=>0.1,
		    				'decimals'=>2,
		    				'boostat'=>5,
		    				'maxboostedstep'=>10,
		    				'prefix'=>'R'
		    			]
		    	]) ?>
			</div>
			
	</div>
	<div class="col-sm-offset-2">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class'=>'btn btn-default']) ?>
	</div>
<?php ActiveForm::end(); ?>
