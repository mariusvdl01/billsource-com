<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\collector\CollectorsBin */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Collectors Bin',
]) . $model->bin_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectors Bins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bin_id, 'url' => ['view', 'id' => $model->bin_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="collectors-bin-update">

		    <h1><?= Html::encode($this->title) ?></h1>

		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>

		</div>
	</div>
</div>
