<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\document\Document */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'status_id',
            'type',
            'business_id',
            'alt_business_name',
            'deleted:boolean',
            'client_id',
            'client_email:email',
            'client_mobile',
            'client_vat',
            'reference_number',
            'issue_date',
            'due_date',
            'discount',
            'amount',
            'paid',
            'comments',
            'marketing',
            'creditor',
            'subtotal',
            'vat',
            'total',
            'pdf',
        ],
    ]) ?>

</div>
