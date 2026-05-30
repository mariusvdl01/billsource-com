<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\individual\IndividualReading */
/* @var $reading common\models\Reading */


$this->title = Yii::t('app', 'Create New Meter Reading');
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="individual-reading-create">

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
