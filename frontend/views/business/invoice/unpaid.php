<?php

use frontend\assets\InvoiceAsset;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billsource - Debtors Management (Unpaid)';
$controller = Yii::$app->controller->id;
$asset = Yii::$app->params['assetBundle'];
InvoiceAsset::register($this);
$img_url = $asset->baseUrl.'/images/';
?>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="invoice-unpaid">
			<h4><?= Html::encode($this->title) ?></h4>
			<?php $form = ActiveForm::begin([
				'id'	=> 'payment-form',
			])?>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
                'rowOptions' => function ($model, $index, $widget, $grid){
                    if($model['read'] == 0) {
                        return ['class' => 'unread'];
                    } else {
                        return [];
                    }
                },
				'columns' => [

					"business_name:text:Customer",
					"total:currency:Amount",
					'comments',
					"due_date:date",
					"status_name:text:Status",
					[
						'class' => 'yii\grid\ActionColumn',
						'header' => 'Action',
						'template' => '{view}{update}',
						'controller' => 'CreditorController',
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
			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>
