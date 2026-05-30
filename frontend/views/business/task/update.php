<?php

use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\catalog\Product;
use common\models\invoice\Invoice;
use common\models\invoice\InvoiceLineManager;
use frontend\assets\PayslipAsset;
use yii\web\View;
use yii\helpers\Html;

/* @var $customers BusinessClientCrm */
/* @var $biller BusinessClient */
/* @var $lineManager InvoiceLineManager */
/* @var $products Product */
/* @var $terms array */
/* @var $invoice Invoice */
/* @var $statuses array */
/* @var $this View */

$this->title = Yii::t('app', 'Edit {modelClass}: #', [
    'modelClass' => 'Task',
]). $task->reference_number;
PayslipAsset::register($this);
?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="invoice-edit">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
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
