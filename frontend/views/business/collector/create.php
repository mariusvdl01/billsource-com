<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\collector\CollectorsBin */

$this->title = Yii::t('app', 'Create Collectors Bin');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectors Bins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="collectors-bin-create">

		    <h1><?= Html::encode($this->title) ?></h1>

		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>

		</div>
	</div>
</div>
