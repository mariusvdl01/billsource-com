<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\business\BusinessClientCrm */

$this->title = Yii::t('app', 'New Customer');
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="business-client-crm-create">
		    <h4><?= Html::encode($this->title) ?></h4>

		    <?= $this->render('_form', [
		        'model' => $model,
		    	'provinces' => $provinces,
		    	'business_id'	=> $business_id,
		    ]) ?>
		</div>
	</div>
</div>