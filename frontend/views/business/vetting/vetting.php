<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */

$this->title = 'Business Vetting';
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="vet-business">
			<?php if(Yii::$app->session->hasFlash('isVetted')) : ?>
				<div class="alert alert-success">Business with Reference: <?= $model->reference; ?> has R
				<?= number_format($model->getTotal(), 2); ?> in outstanding bills.</div>
			<?php elseif (Yii::$app->session->hasFlash('isNotVetted')) : ?>
				<div class="alert alert-info">Business with Reference: <?= $model->reference; ?> has no outstanding bills.</div>
			<?php endif; ?>
			
			<h3 class="text-center"><?= Html::encode($this->title) ?></h3>
			<p class="text-center">Please enter ID, Business Registration, Mobile Number or Email</p>
			<br>
		    <div class="row">
		        <div class="col-sm-offset-2 col-sm-8">
		            <?php $form = ActiveForm::begin(['id' => 'vet-business-form']); ?>
		                <?= $form->field($model, 'reference')->label(false) ?>
		                <div class="form-group">
		                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
		                </div>
		            <?php ActiveForm::end(); ?>
		        </div>
		    </div>
		</div>
	</div>
</div>