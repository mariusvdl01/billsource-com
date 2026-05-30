<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billsource - Invoice Disputed';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="invoice-disputed">
            <h4><?= Html::encode($this->title) ?></h4>
            <br />
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                    	'class' => 'yii\grid\SerialColumn',
                    	'header' => 'Item'
                    ],

                    "business_name:text:Customer",
                	"reference_number:text:Reference #",
                	"total:currency:Amount",
                	"status_name:text:Status",
                	'comments',
                	"due_date:date",

                    [
                    	'class' => 'yii\grid\ActionColumn',
                    	'header' => 'Action',
                        'template' => '{view} {update}',
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

