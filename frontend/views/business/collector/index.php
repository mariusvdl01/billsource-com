<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use common\models\invoice\Invoice;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\collector\BinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Collectors Bin');
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
$bill = new Invoice();
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="collectors-bin-index">

            <h3><?= Html::encode($this->title) ?></h3>
            <?php $form = ActiveForm::begin([
                'id'	=> 'payment-form',
                'action' => '/business/collector'
            ])?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    "invoice.reference_number:text:Reference",
                    [
                        'header' => 'Biller',
                        'content' => function($model, $key, $index) use($bill) {
                            return $bill->getBillerName($model->invoice->business_id);
                        }
                    ],
                    "invoice.alt_business_name:text:Debtor",
                    "invoice.total:currency:Total Overdue",
                    "invoice.due_date:date:Overdue date",
                    [
                        'class' 	=> 'yii\grid\CheckboxColumn',
                        'name'		=> 'invoice_ids',
                        'multiple'	=> true,
                        'header' 	=> 'Select',
                        'checkboxOptions' => function($data) {
                            return ['value' => $data->invoice_id];
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'View',
                        'template' => '{view}',
                        'controller' => 'CollectorController',
                        'urlCreator' => function($action, $model, $key, $index) use($controller) {
                            return '/' . $controller . '/' . $action . '?id=' . $model->invoice_id;
                        },
                    ],
                ],
            ]); ?>
            <div class="col-sm-offset-10">
                <?= Html::submitButton('Make Factor', [
                    'class' => 'btn btn-default',
                ]) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
