<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\Product */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Product',
]) . ' ' . $model->name;
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="product-update">
		    <h4><?= Html::encode($this->title) ?></h4>

		    <?= $this->render('_form', [
		        'model' => $model,
		    	'category' => $category,
		    	'category_id' => $category_id,
		    ]) ?>
		</div>
	</div>
</div>
