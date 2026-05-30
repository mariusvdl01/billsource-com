<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Tickets (Processing)';
$controller = Yii::$app->controller->id;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="ticket-processig">
            <h4><?= Html::encode($this->title) ?></h4>
        	<br />

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

            		"trading_name:text:Biller",
            		'reference_number',
            		'comments',
                	[
                		'attribute' => 'due_date',
                		'label'		=> 'Duration',
                		'format'	=> 'date',
            		],
                	[
                		'class' => 'yii\grid\ActionColumn',
                		'header' => 'Action',
                		'template' => '{view}',
                		'urlCreator' => function($action, $model, $key, $index) use($controller) {
                			return '/' . $controller . '/' . $action . '?id=' . $model['id'];
                		},
                	],
                ],
            ]); ?>
        </div>
    </div>
</div>