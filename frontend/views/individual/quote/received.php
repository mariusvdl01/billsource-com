<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\QuoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quote Received';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="quote-received">
            <h4><?= Html::encode($this->title) ?></h4>
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
                        //'attribute' => 'id',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::Button(
                                '<i class="fa fa-check"></i>Accept',
                                [
                                    'id'=>'quote-accept-button',
                                    'class'=>'button btn btn-default',
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to accept the quote?'),
                                    'data-method' => 'post',
                                    'value' => $model['id']
                                ]
                            );
                        }
                    ],
                    [
                    	'class' => 'yii\grid\ActionColumn',
                    	'header' => 'Action',
                    	'template' => '{view}',
                    	'controller' => 'InvoiceController',
                    	'urlCreator' => function($action, $model, $key, $index) use($controller) {
                    		//return '/' . $controller . '/' . $action . '?id=' . $model['id'];
                            return Url::to(['individual/quote/view', 'id' => $model{'id'}]);
            			},
            			'buttons' => [
            				'view' => function ($url) {
            					return Html::a('', $url, [
                                    'target' => '_blank',
                                    'class'  => 'glyphicon glyphicon-eye-open',
                                    'data-pjax' => false
            					]);
            				}
            			]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

