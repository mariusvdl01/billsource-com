<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Task Management (Completed)');
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="completed-index">
            <h3><?= Html::encode($this->title) ?></h3>
            <p>
                <?= Html::a('<i class="fa fa-plus"></i> Create Task', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'title',
                    'description:ntext',
                    [
                        'attribute' => 'due_date',
                        'format' => 'date',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'due_date',
                            'options' => ['placeholder' => 'Select date'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'autoclose' => true
                            ]
                        ])
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('', $url, [
                                    'target' => '_blank',
                                    'class' => 'glyphicon glyphicon-eye-open'
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