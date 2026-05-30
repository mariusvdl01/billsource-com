<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bills Management - Refunds';
$assetsBundle = Yii::$app->params['assetBundle'];
$image_dir =  $assetsBundle->baseUrl . '/images/';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="bill-refunds">
            <h4><?= Html::encode($this->title) ?></h4>
        	<br />

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

            		"trading_name:text:Biller",
            		'reference_number',
            		'total:currency:Total',
                    "vat:currency:VAT",
                    "discount:currency:Discount",
            		'comments',
                	[
                		'attribute' => 'due_date',
                		'label'		=> 'Due Date',
                		'format'	=> 'date',
            		],
                	/*[
                		'label'	=> 'Status',
                		'content' => function($data) {
                			if(isset($data['paid']) && $data['paid'] == 1) {
                				return 'Paid';
                			} elseif(isset($data['business_id']) && $data['business_id'] == 0) {
                				return 'Self Captured';
                			} elseif(isset($data['allow_payment']) && $data['allow_payment'] == 0) {
                				return 'Handed over';
                			} else {
                				return 'Make Payment';
                			}
                		}
                	],*/
                	[
                		'class' => 'yii\grid\ActionColumn',
                		'header' => 'Action',
                		'template' => '{view}',
                		'urlCreator' => function($action, $model, $key, $index) use($controller) {
                			return '/' . $controller . '/' . $action . '?id=' . $model['id'];
                		},
                		'buttons' => [
                			'view' => function ($url, $model, $key) {
                				return Html::a('', $url, [
                						'target' => '_blank',
                						'class'  => 'glyphicon glyphicon-eye-open'
                				]);
                			}
                		]
                	],
                ],
            ]); ?>
        </div>
    </div>
</div>