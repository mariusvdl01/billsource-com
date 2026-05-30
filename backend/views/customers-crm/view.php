<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\clients\BusinessClientCrm */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Crms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-client-crm-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->crm_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->crm_id], [
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
            //'crm_id',
            //'is_active',
            'trading_name:ntext',
            'email:email',
            'business.trading_name',
            'id_number:text',
            'registration_number',
            'registered_name',
            'vat_reg_number:text',
            'phone_number',
            'address_street',
            'address_region',
            'province.province_name',
            'address_code',
            'fax_number',
            'first_name',
            'last_name',
            'mobile',
            //'uses',
            //'last_used',
            //'created_at',
            //'updated_at',
            //'deleted',
            [
                'label' => 'Type',
                'value' => $model->is_business ? 'Business' : 'Individual',
            ],
        ],
    ]) ?>

</div>
