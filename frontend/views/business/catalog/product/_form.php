<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use kartik\touchspin\TouchSpin;
use yii\helpers\Html;

$conditions = [
	'na' => 'Not Applicable',
	'new' => 'New',
	'used' => 'Used',
	'refurbished' => 'Refurbished',
]
?>
<?php $form = ActiveForm::begin([
	'type' => ActiveForm::TYPE_HORIZONTAL,
	'formConfig' => [
		'labelSpan'=>3,
		'deviceSize' => ActiveForm::SIZE_SMALL
	],
]) ?>

<div class="form-group kv-fieldset-inline">
	<!-- your fields -->
	<?= Html::activeLabel($model, 'id', [
        'label'=>'Product', 
        'class'=>'col-sm-2 control-label'
    ]); ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'name',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Name']); ?>
	    </div>
	    <div class="col-sm-3">
	    	<?php echo Select2::widget([
	    		'name' => 'category_id',
	    		'data' => $category,
	    		'value' => isset($category_id) ? $category_id : '',
	    		'options' => [
	    			'placeholder' => 'Select a category ...',
	    			//'multiple' => true
	    		],
	    		'pluginOptions' => [
	    			'allowClear' => true
	    		],
	    	]); ?>
	    </div>
	    <div class="col-sm-3">
	        <?= $form->field($model, 'reference',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Reference']); ?>
	    </div>
	<?= Html::activeLabel($model, 'product_id', [
        'label'=>'Description', 
        'class'=>'col-sm-2 control-label'
    ]); ?>
	   <div class="col-sm-10">
	        <?= $form->field($model, 'description',[
	            'showLabels'=>false
	        ])->textArea(['placeholder'=>'']); ?>
	    </div>
	<?= Html::activeLabel($model, 'id', [
        'label'=>'CP/SP', 
        'class'=>'col-sm-2 control-label'
    ]); ?>
    	<div class="col-sm-4">
	        <?= $form->field($model, 'cost_price',[
	            'showLabels'=>false
	        ])->widget(TouchSpin::className(), ['pluginOptions' => [
			        'prefix' => 'R', 
			        'decimals' => 2, 
			        'min'=>0.00, 
			        'max'=>1000000,
			        'step' => 0.01,
			        'verticalbuttons' => true,
			        'verticalupclass' => 'glyphicon glyphicon-plus',
        			'verticaldownclass' => 'glyphicon glyphicon-minus',
	        	],
	        	'options' => ['placeholder' => 'Adjust Cost Price...']
	        ]); ?>
	    </div>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'selling_price',[
	            'showLabels'=>false
	        ])->widget(TouchSpin::className(), ['pluginOptions' => [
			        'prefix' => 'R', 
			        'decimals' => 2, 
			        'min'=>0.00, 
			        'max'=>1000000,
			        'step' => 0.01,
			        'verticalbuttons' => true,
			        'verticalupclass' => 'glyphicon glyphicon-plus',
        			'verticaldownclass' => 'glyphicon glyphicon-minus',
	        	],
	        	'options' => ['placeholder' => 'Adjust Selling Price...']
	        ]); ?>
	    </div>
    	<div class="col-sm-2">
	        <?= $form->field($model, 'quantity',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Quantity']); ?>
	    </div>
	    
	    
	<?= Html::activeLabel($model, 'id', [
        'label'=>'Dimensions', 
        'class'=>'col-sm-2 control-label'
    ]); ?>
	    <div class="col-sm-2">
	        <?= $form->field($model, 'width',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Width']); ?>
	    </div>
	    <div class="col-sm-2">
	        <?= $form->field($model, 'height',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Height']); ?>
	    </div>
	    <div class="col-sm-3">
	        <?= $form->field($model, 'depth',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Depth']); ?>
	    </div>
	    <div class="col-sm-3">
	        <?= $form->field($model, 'weight',[
	            'showLabels'=>false
	        ])->textInput(['placeholder'=>'Weight']); ?>
	    </div>
	  
	<?= Html::activeLabel($model, 'product_id', [
        'label'=>'Active', 
        'class'=>'col-sm-2 control-label'
    ]); ?> 
    	<div class="col-sm-2">
	        <?= $form->field($model, 'active',[
	            'showLabels'=>false
	        ])->widget(SwitchInput::className(), ['pluginOptions' => ['size' => 'small']]); ?>
	    </div>
	<?= Html::activeLabel($model, 'id', [
        'label'=>'Out of Stock', 
        'class'=>'col-sm-2 control-label'
    ]); ?>
	    <div class="col-sm-2">
	        <?= $form->field($model, 'out_of_stock',[
	            'showLabels'=>false
	        ])->widget(SwitchInput::className(), [
	        	'pluginOptions' => ['size' => 'small'],
	        ]); ?>
	    </div>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'condition',[
	            'showLabels'=>false
	        ])->widget(Select2::className(), [
	        	'data' => $conditions,
	        	'options' => ['placeholder' => 'Select a condition ...'],
	        	'pluginOptions' => [
	        		'allowClear' => true
	        	],
	        ]); ?>
	    </div>
</div>
	<div class="col-sm-offset-9 col-sm-3">
	   <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
       <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
	<?php ActiveForm::end(); ?>