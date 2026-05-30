<?php 

namespace console\controllers;

use common\models\jobs\DebitOrderReport;
use yii\console\Controller;

class ReportController extends Controller
{
	/**
	 * Generate a monthly debit order report
	 *
	 */
	public function actionDebitOrder() {
		$report = new DebitOrderReport;
		$report->genereateReport();
	}
}
?>