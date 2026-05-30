<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\customer\Customer */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->user_id], [
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
            //'user_id',
            'email:email',
            [
                'label' => 'Status',
                'value' => $model->status ? 'Enabled' : 'Disabled',
            ],
            [
                'label' => 'Type',
                'value' => $model->business_user ? 'Business' : 'Individual',
            ],
            [
                'label' => 'Activated?',
                'value' => $model->is_activated ? 'Yes' : 'No',
            ],
            'last_login:datetime',
        ],
    ]) ?>

</div>
