<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\business\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'My Customers');
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="business-client-crm-index">
            <h4><?= Html::encode($this->title) ?></h4>
            <p>
                <?= Html::a(Yii::t('app', 'Add New Customer'), ['create'], ['class' => 'btn btn-default']) ?>
            </p>
        	<br />
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                    	'class' => 'yii\grid\SerialColumn',
                    	'header' => 'Item',		
            		],
        			[
        				'label' => 'Customer',
        				'content' => function($model, $index, $key, $column) {
        					if(empty($model->trading_name)) {
        						return $model->first_name . ' ' . $model->last_name;
        					}
        					return $model->trading_name;
        				}
        			],
                    [
                    	'label'	=> 'Contact Person',
                    	'content' => function($model, $index, $key, $column) {
                    		return $model->first_name . ' ' . $model->last_name;
            			}
                    ],
                    [
                    	'label'	=> 'Type',
                    	'content' => function($model, $index, $key, $column) {
                    		if($model->is_business)
                    			return 'Business';
                    		else
                    			return 'Individual';
                    	}
                    ],
                    'mobile:text',
                	'email:email',
        			[
        				'label' 	=> 'Status',
        				'content' 	=> function($model, $index, $key, $column) {
        					if($model->is_active)
        						return 'Enabled';
        					
                    		return 'Disabled';
            			}
        			],
                    [
                    	'class' => 'yii\grid\ActionColumn',
                    	'template' => '{update}{delete}',
                    	'urlCreator' => function($action, $model, $key, $index) use($controller) {
                    		return '/' . $controller . '/' . $action . '?id=' . $model->id;
                    	}
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
