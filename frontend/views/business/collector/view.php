<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\collector\CollectorsBin */

$this->title = $model->bin_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectors Bins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="collectors-bin-view">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->bin_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->bin_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'bin_id',
                    'invoice_id',
                    'collector_id',
                    'paid',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>

        </div>
    </div>
</div>
