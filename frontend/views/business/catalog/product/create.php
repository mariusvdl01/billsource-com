<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Add new product';
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="catalog-product-create">
			<h3><?= Html::encode($this->title) ?></h3>
			<p><font color="#CC0000">*</font> Required field</p>

			<?= $this->render('_form', [
				'model' => $model,
				'category' => $category,
			]) ?>   
		</div>
	</div>
</div>