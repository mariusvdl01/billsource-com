<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ticket Management (Planning)');
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="planning-index">
            <h3><?= Html::encode($this->title) ?></h3>
            <?php Pjax::begin(); ?>    <?= GridView::widget([
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

                        'alt_business_name:text:Customer',
                        'reference_number:text:Reference',
                        'client_id:text:ID Number',
                        'client_email:email',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Action',
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
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>