<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Social Networks - Invite';
BillsourceAsset::register($this);

?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="vault-create">
			<h4><?= Html::encode($this->title) ?></h4>
			
		</div>
	</div>
</div>