<?php

namespace common\models\jobs;

use common\models\DebitOrder;
use yii\base\Model;

class DebitOrderReport extends Model
{
	
	public function genereateReport()
	{
		$debitOrder = new DebitOrder;
		
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="debitorder'.date("Ymd").'.csv"');
		echo  '"DATE","CLIENT","REG_NO","BANK","BRANCH","BRANCHCODE","ACCOUNT","AMOUNT"'."\r\n";
		if(($orders = $debitOrder->findDebitOrdersAll()) !== false)
		{
			foreach($orders as $order)
			{
				echo '"'.$order->order_date.'",';
				echo '"'.$order->reference->registered_name.'",';
				echo '"'.$order->reference->registration_number.'",';
				echo '"'.$order->order_bank.'",';
				echo '"'.$order->order_bank_branch.'",';
				echo '"'.$order->order_branch_code.'",';
				echo '"'.$order->order_bank_account.'",';
				echo $order->order_amount."\r\n";
			}
		}
	}
}
?>