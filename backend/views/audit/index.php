<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AuditTrailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Audit Trails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-trail-index">

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'header' => 'User', 
                'content' => function($model, $key, $index, $action) {
                    if($model->user->business_user)
                        return $model->businessClient->contact_person . ' (' . $model->businessClient->getCompanyName() . ')';

                    return $model->individualClient->getFullName();
                }
            ],
            
            "audit_form:text:Resource",
            "audit_action:text:Action",
            "audit_memo:ntext:Description",
            "ip_addr:text:IP Address",
            "created_at:date:Date",

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
