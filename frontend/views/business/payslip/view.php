<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\invoice\Payslip */

$this->title = $model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payslips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="payroll-view">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-primary']) ?>
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
                    'business_id',
                    'emp_id',
                    'emp_id_number',
                    'emp_email:email',
                    'emp_mobile',
                    'hours',
                    'description',
                    'rate',
                    'amount',
                    'reference_number',
                    'pay_date',
                    'bonus_commission',
                    'deductions',
                    'subtotal',
                    'tax',
                    'uif',
                    'total',
                ],
            ]) ?>

        </div>
    </div>
</div>