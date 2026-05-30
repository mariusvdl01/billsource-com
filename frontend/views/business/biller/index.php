<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\business\BillerSearch */
/* @var $dataProvider yii\data\SqlDataProvider */

$this->title = 'Billsource - Company Management';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="user-index">
            <h4><?= Html::encode($this->title) ?></h4>
            <p>
                <?= Html::a('Create New Company', ['create'], ['class' => 'btn btn-default']) ?>
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
        			
                    "trading_name:text:Biller",
                	"contact_person:text:Contact",
        			'phone_number',
        			'email',
                	[
                		'header'		=> 'Status',
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
                    		return '/' . $controller . '/' . $action . '?id=' . $model['user_id'];
            			},
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

