<?php

use frontend\assets\IndividualAsset;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bills Management - Unpaid';
$assetBundle = Yii::$app->params['assetBundle'];
IndividualAsset::register($this);
$img_url = $assetBundle->baseUrl.'/images/';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="invoice-index">
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

            		"trading_name:text:Company",
                    'reference_number',
            		'amount:currency:Total',
            		'comments',
                	//"status_name:text:Status",
                	[
                		'attribute' => 'due_date',
                		'label'		=> 'Due Date',
                		'format'	=> 'date',
            		],
                	[
                		'label'	=> 'Action',
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
                	],
                	[
                		'class' 	=> 'yii\grid\CheckboxColumn',
                		'name'		=> 'status',
                		'multiple'	=> true,
                		'header' 	=> 'Pay',
                		'checkboxOptions' => function($data) {
                			if($data['minimum_days'] >= 121 || (isset($data['business_id']) && $data['business_id'] == 0)) {
                				return ['disabled'=>true];
                			}
             	    		return ['value' => $data['id']];
             			}
                	],
                	[
                		'class' => 'yii\grid\ActionColumn',
                		'header' => 'PDF',
                		'controller' => 'InvoiceController',
                		'template' => '{view}',
                		'urlCreator' => function($action, $model, $key, $index) use($controller) {
                			return '/' . $controller . '/' . $action . '?id=' . $model['id'];
                		},
                		'buttons' => [
            				'view' => function ($url, $model, $key) use($img_url) {
            					return Html::a('<img src="' . $img_url . "{$model['image']}" . '" />', $url, [
            							'target' => '_blank',
            							'class'  => ''
            					]);
            				}
            			]
                	],
                ],
            ]); ?>
            <div class="col-sm-offset-10">
            	<?= Html::submitButton('Make Payment', [
        			'class' => 'btn btn-default',
               	]) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>