<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\business\models\BusinessClientUser */

$this->title = Yii::t('app', 'Add new user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Client Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="business-client-user-create">
			<h4><?= Html::encode($this->title) ?></h4>
		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>

		</div>
	</div>
</div>
