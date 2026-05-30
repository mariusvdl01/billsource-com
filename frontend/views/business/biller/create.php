<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\business\BillerForm */

$this->title = Yii::t('app', 'Create new company');
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="business-client-user-create">
			<h3><?= Html::encode($this->title) ?></h3>
		    <?= $this->render('_form', [
		        'model' => $model,
		        'titles' => $titles,
		        'provinces' => $provinces
		    ]) ?>

		</div>
	</div>
</div>
