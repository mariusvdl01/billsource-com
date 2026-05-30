<?php

/* @var $this yii\web\View */
/* @var $employees common\models\business\BusinessEmployee */
/* @var $biller common\models\business\BusinessClient */
/* @var $payslip common\models\invoice\Payslip */
/* @var $lineManager common\models\invoice\InvoiceLineManager */
/* @var $products common\models\catalog\Product */
/* @var $terms array */

use yii\helpers\Html;

$this->title = Yii::t('app', 'New Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Task'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\frontend\assets\PayslipAsset::register($this);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="payroll-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'employees'		=> $employees,
                'statuses'      => $statuses,
                'biller' 		=> $biller,
                'task' 		=> $task,
                'lineManager'	=> $lineManager,
                'openTasks'		=> $openTasks
            ]) ?>

        </div>
    </div>
</div>