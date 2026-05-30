<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\customer\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <p>
        <?= Html::a(Yii::t('app', 'Create Customer'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'email:email',
            [
                'header' => 'Name',
                'content' => function($model, $index, $key, $action) {
                    if($model->business_user == true) 
                        return $model->businessClient->getCompanyName();

                    return $model->individualClient->getFullName();
                }
            ],
            [
                'header' => 'Category',
                'content' => function($model, $index, $key, $action) {
                    if($model->business_user == true) 
                        return 'Company';

                    return 'Individual';
                }
            ],
            [
                'header' => 'Status',
                'content' => function($model, $index, $key, $action) {
                    if($model->status == true) 
                        return 'Enabled';

                    return 'Disabled';
                }
            ],
            // 'is_activated',
            'last_login',
            "created_at:date:Customer Since",
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
