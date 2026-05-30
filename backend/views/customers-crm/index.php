<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\clients\BusinessClientCrmSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customer Crms');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-crm-index">
    <p>
        <?= Html::a(Yii::t('app', 'Create Customer Crm'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'business.trading_name',
            'email:email',
            [
                'header' => 'Status',
                'content' => function($model, $key, $index, $action) {
                    return $model->is_active ? 'Enabled' : 'Disabled';
                }
            ],
            'id_number:text:ID Number',
            // 'trading_name:ntext',
            // 'registration_number',
            // 'registered_name',
            // 'vat_reg_number',
            // 'phone_number',
            // 'address_street',
            // 'address_region',
            // 'address_province',
            // 'address_code',
            // 'fax_number',
            // 'first_name',
            // 'last_name',
            // 'mobile',
            // 'uses',
            // 'last_used',
            // 'created_at',
            // 'updated_at',
            // 'deleted',
            [
                'header' => 'Type',
                'content' => function($model, $key, $index, $action) {
                    return $model->is_business ? 'Business' : 'Individual';
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
