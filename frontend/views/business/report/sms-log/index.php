<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sms\SmsLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'SMS Count');
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="sms-log-index">

            <h4><?= Html::encode($this->title) ?></h4>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'period',
                        'label' => 'Period',
                        'content' => function ($model, $key, $index, $grid) {
                            return (new \DateTime(substr_replace($model->period, '-', 4, 0)))->format('M Y');
                        }
                    ],
                    "count:text:Monthly Total",
                ],
            ]); ?>

        </div>
    </div>
</div>
