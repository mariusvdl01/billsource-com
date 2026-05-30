<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\document\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Documents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">
    <p>
        <?= Html::a(Yii::t('app', 'Create Document'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'business.trading_name:text:Biller',
            'alt_business_name:text:Debtor',
            [
                'header' => 'Type',
                'content' => function($model, $key, $index, $action) {
                    switch($model->type) {
                        case 'UTB':
                            return 'Utility Bill';
                            break;

                        case 'QTN':
                            return 'Quote';
                            break;

                        default:
                            return 'Invoice';
                            break;
                    }
                }
            ],
            // 'deleted:boolean',
            // 'client_id',
            // 'client_email:email',
            // 'client_mobile',
            // 'client_vat',
            'total:currency',
            'reference_number',
            'status.name:text:Status',
            // 'issue_date',
            // 'due_date',
            // 'discount',
            // 'amount',
            // 'paid',
            // 'comments',
            // 'marketing',
            // 'creditor',
            // 'subtotal',
            // 'vat',
            // 'pdf',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
