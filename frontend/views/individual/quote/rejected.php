<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\QuoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quote Rejected';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="quote-rejected">
            <h4><?= Html::encode($this->title) ?></h4>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                    	'class' => 'yii\grid\SerialColumn',
                    	'header' => 'Item'
                    ],

                    "trading_name:text:Company",
                	"discount:currency:Discount",
                	"total:currency:Amount",
                	'comments',
                	"due_date:date",
                    [
                    	'class' => 'yii\grid\ActionColumn',
                    	'header' => 'Action',
                    	'template' => '{view}',
                    	'controller' => 'InvoiceController',
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