<?php

use common\models\Status;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billsource - Debtors Management (Unpaid)';
$controller = Yii::$app->controller->id;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="invoice-index">
            <h4><?= Html::encode($this->title) ?></h4>
            <p>
        		<?= Html::a('Add New Invoice', ['create'], ['class' => 'btn btn-default']) ?>
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

                    "client_email:text:Customer",
                    "reference_number:text:Invoice Ref",
                    [
                        'header' => 'Status',
                        'content' => function($model, $key, $index) {

                            if($model['status_id'] == Status::findOne(['code' => Status::STATUS_SENT])->id)
                                return 'Unpaid';
                        }
                    ],
                    'comments',
                    "due_date:date",
                    "total:currency:Amount",
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view} {update}',
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
                'options'=>['class'=>'grid-view gridview-newclass'],
            ]); ?>
        </div>
    </div>
</div>
