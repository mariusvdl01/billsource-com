<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $employees common\models\business\BusinessEmployee */
/* @var $biller common\models\business\BusinessClient */
/* @var $payslip common\models\invoice\Payslip */
/* @var $lineManager common\models\invoice\InvoiceLineManager */
/* @var $products common\models\catalog\Product */
/* @var $terms array */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Payslip',
]) . $payslip->reference_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payslips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $payslip->id, 'url' => ['view', 'id' => $payslip->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
\frontend\assets\PayslipAsset::register($this);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="payroll-update">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form',[
                'employees'		=> $employees,
                'biller' 		=> $biller,
                'payslip' 		=> $payslip,
                'lineManager'	=> $lineManager,
                'statuses'		=> $statuses,
                'products'		=> $products,
                'terms'			=> $terms,
            ]) ?>

        </div>
    </div>
</div>