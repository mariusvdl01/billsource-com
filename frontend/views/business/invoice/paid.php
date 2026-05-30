<?php

use frontend\assets\InvoiceAsset;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billsource - Debtors Management (Paid)';
$controller = Yii::$app->controller->id;
$asset = Yii::$app->params['assetBundle'];
InvoiceAsset::register($this);
$img_url = $asset->baseUrl.'/images/';
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="cash-invoice-paid">
			<h4><?= Html::encode($this->title) ?></h4>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

					"business_name:text:Customer",
                    'reference_number',
					"total:currency:Total",
                    "vat:currency:VAT",
                    "discount:currency:Discount",
					'comments',
					"due_date:date",
					//"status_name:text:Status",
					[
						'class' => 'yii\grid\ActionColumn',
						'header' => 'Action',
						'template' => '{view}',
						'urlCreator' => function($action, $model, $key, $index) use($controller) {
							return '/' . $controller . '/' . $action . '?id=' . $model['id'];
						},
						'buttons' => [
							'view' => function ($url, $model, $key) use($asset, $img_url) {
								return Html::a('<img src="' . $img_url . "{$model['image']}" . '" />', $url, [
									'target' => '_blank',
									'class'  => ''
								]);
							}
						]
					],
				],
			]); ?>
		</div>
	</div>
</div>
