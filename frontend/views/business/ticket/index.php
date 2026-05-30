<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\invoice\PayslipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payroll Management');
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="payslip-index">

            <h3><?= Html::encode($this->title) ?></h3>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a(Yii::t('app', 'Create Payslip'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [

                        'alt_business_name:text:Name',
                        'reference_number:text:Reference',
                        'client_id:text:ID Number',
                        'client_email:email',
                        'total',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Action',
                            'urlCreator' => function($action, $model, $key, $index) use($controller) {
                                return '/' . $controller . '/' . $action . '?id=' . $model['invoice_id'];
                            },
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('', $url, [
                                        'target' => '_blank',
                                        'class'  => 'glyphicon glyphicon-eye-open'
                                    ]);
                                }
                            ]
                        ],
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>