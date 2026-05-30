<?php

use frontend\assets\InvoiceAsset;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billsource - Creditors Management (Unpaid Bills)';
$controller = Yii::$app->controller->id;
$asset = Yii::$app->params['assetBundle'];
InvoiceAsset::register($this);
$img_url = $asset->baseUrl.'/images/';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="invoice-creditors">
            <h4><?= Html::encode($this->title) ?></h4>
        	<div class="alert alert-danger" role="alert">
        		<p>
        			<strong>Your owe your creditors
        				<?= (isset($header['credit_total'])) ?
                            Yii::$app->formatter->asCurrency($header['credit_total']) : '0.00';
        				?>
        				<br />
        				
                       	<?php 
                            $date = isset($oldest['due_date']) ? $oldest['due_date'] : '';
                            $dueDate = (new \DateTime($date))->format('l, d M Y');
                         	if(!empty($oldest)) {
                            	$old = 'Your oldest bill is from ';
                            	$old .= "{$oldest['trading_name']} for ";
                            	$old .= Yii::$app->formatter->asCurrency($oldest['total']);
                                $old .= $oldest['paid'] == 0 ? ' (unpaid) | ' : ' (paid) | ';
                           		$old .= 'Due date: ' . $dueDate;
                            	echo "{$old}";
                         	}
                    	?>
        			</strong>
        		</p>
        	</div>
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
                	"total:currency:Amount",
                	'comments',
                	"due_date:date",
                	"description:text:Status",
                	[
                		'class' 	=> 'yii\grid\CheckboxColumn',
                		'name'		=> 'status',
                		'multiple'	=> true,
                		'header' 	=> 'Pay',
                		'checkboxOptions' => function($data) {
                			if($data['minimum_days'] >= 121) {
                				return ['disabled'=>true];
                			}
             	    		return ['value' => $data['invoice_id']];
             			}
                	],
                    [
                    	'class' => 'yii\grid\ActionColumn',
                    	'header' => 'Action',
                    	'template' => '{view}',
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
            <div class="col-sm-offset-10">
            	<?= Html::submitButton('Make Payment', [
        			'class' => 'btn btn-primary',
               	]) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
