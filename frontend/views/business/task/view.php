<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = 'Task #: ' . $model->reference_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <p>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <!-- <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this task?'),
                        'method' => 'post',
                    ],
                ]) ?> -->
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'reference_number',
                    'comments:text',
                    'issue_date',
                    [
                        'attribute' => 'status_id',
                        'value' => $model->status->name,
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div> 