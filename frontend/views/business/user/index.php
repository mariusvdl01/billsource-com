<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\business\UserSearch */
/* @var $dataProvider yii\data\SqlDataProvider */

$this->title = 'Billsource - User Management';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="user-index">
            <h4><?= Html::encode($this->title) ?></h4>
            <p>
                <?= Html::a('Create New User', ['create'], ['class' => 'btn btn-default']) ?>
            </p>
          
        	<br />
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                    	'class' => 'yii\grid\SerialColumn',
                    	'header' => 'Item'
                    ],
        			
                    "email:text:Email",
                	[
                		'attribute' => 'item_name',
                		'label' 	=> 'Role',
                		'content' 	=> function($data) {
                			if(isset($data['item_name'])
        						&& ('singleUserAdmin' == $data['item_name'] || 'businessAdmin' == $data['item_name']))
                				return 'System Admin';
                			elseif('loader' == $data['item_name'] && isset($data['item_name']))
                				return 'Manager';
        					else
        						return 'Read Access';
            			}
                	],
                	[
                		'attribute' => 'status',
                		'label'		=> 'Status',
                		'content'	=> function($data) {
                			if(isset($data['status']) && $data['status'] == '0')
                				return 'Disabled';
                			else
                				return 'Enabled';
                		}
        			],
        			
                    [
                    	'class' => 'yii\grid\ActionColumn',
                    	'header' => 'Action',
                    	'controller' => 'UserController',
                    	'template' => '{update}',
                    	'urlCreator' => function($action, $model, $key, $index) use($controller) {
                    		return '/' . $controller . '/' . $action . '?id=' . $model['id'];
            			},
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

