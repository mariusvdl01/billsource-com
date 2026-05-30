<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Add a vault';
$assetsBundle = Yii::$app->params['assetBundle'];
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="vault-create">
			<h4>Vault Tax Invoice</h4>
			<br>
			<?php $form = ActiveForm::begin([
				'id' => 'individual-vault-form',
				'layout' => 'horizontal',
				'options' => [
					'enctype' => 'multipart/form-data',
				]
			])?>
				
				<?= $form->field($model, 'issue_date')->widget(DatePicker::className(), [
						'value' => '',
						'options' => ['placeholder' => 'Select issue date ...'],
						'pluginOptions' => [
							'format' => 'yyyy-m-d',
							'todayHighlight' => true
						]
				]) ?>
				<?= $form->field($model, 'due_date')->widget(DatePicker::className(), [
						'value' => '',
							'options' => ['placeholder' => 'Select due date ...'],
							'pluginOptions' => [
								'format' => 'yyyy-m-d',
								'todayHighlight' => true
						]
				]) ?>
				<?= $form->field($model, 'status_id')->dropDownList($statuses)->label('Status ID') ?>
				<?= $form->field($model, 'business_name') ?>
				<?= $form->field($model, 'amount') ?>
				<?= $form->field($model, 'terms') ?>
				<?= $form->field($model, 'invoice_file')->fileInput() ?>
				<div class="form-group">
		        	<div class="col-sm-offset-3 col-sm-5">
		        		<?= Html::a('Home', ['/individual/profile'], ['class'=>'btn btn-default']) ?>
			        	<?= Html::submitButton('Save vault', ['class' => 'btn btn-primary', ])?>
					</div>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>