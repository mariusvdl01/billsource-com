<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\business\UserForm */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Company',
]) . ' ' . $model->trading_name;
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="business-client-user-update">
		    <h4><?= Html::encode($this->title) ?></h4>

		    <?= $this->render('_form', [
		        'model' => $model,
		        'titles' => $titles,
		        'provinces' => $provinces,
		    ]) ?>

		</div>
	</div>
</div>
