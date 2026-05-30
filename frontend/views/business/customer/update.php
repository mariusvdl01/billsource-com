<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\business\BusinessClientCrm */

$this->title = Yii::t('app', 'Edit {modelClass}: ', [
    'modelClass' => 'Customer',
]) . ' ' . !empty($model->trading_name) ? $model->trading_name : 
			$model->first_name . ' ' . $model->last_name;
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="business-client-crm-update">
		    <h4><?= Html::encode($this->title) ?></h4>

		    <?= $this->render('_form', [
		        'model' => $model,
		    	'provinces' => $provinces,
		    	'business_id' => $business_id,
		    ]) ?>
		</div>
	</div>
</div>
