<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($userBillRequest, 'user_id')->hiddenInput()->label(false) ?>
	<?= $form->field($userBillRequest, 'is_business_user')->hiddenInput()->label(false) ?>
	<?= $form->field($userBillRequest, 'request_id')
		->checkboxList($billRequests)
		->label('Select the bills you would like to pay.') 
	?>
	<?= Html::submitButton('Save changes', [
		'class' => 'btn btn-primary',
	]) ?>

<?php ActiveForm::end(); ?>