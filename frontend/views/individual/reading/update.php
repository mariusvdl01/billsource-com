<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\individual\IndividualReading */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Individual Reading',
]) . ' ' . $model->entity_id;
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="individual-reading-update">

		    <h4><?= Html::encode($this->title) ?></h4>
			<br>
		    <?= $this->render('_form', [
		        'model' => $model,
		    	'read' => $read,
		    	'reading' => $reading
		    ]) ?>

		</div>
	</div>
</div>
