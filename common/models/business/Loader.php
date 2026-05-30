<?php 

namespace common\models\business;

use Yii;
use common\models\invoice\Invoice;

class Loader extends Reader {
	
	protected $permittedRoles = [
		'loader'
	];
	
	public function getInvoices($bus_id)
	{
		$data = '';
		$sql = 'SELECT `business_id` , IFNULL(`period`, DATE_FORMAT(CURRENT_DATE(), \'%Y%m\' ) ) `date`,
				IFNULL(`count`, 0) `count`, `maximum_limit_invoices` FROM `business_client` `b` 
				LEFT JOIN `business_profile` `c` ON `c`.`profile_id` = `b`.`profile_id`
				LEFT JOIN (SELECT * FROM `invoice_log` WHERE `period`  = 
				DATE_FORMAT(CURRENT_DATE , \'%Y%m\' )) `a` ON `a`.`business_id` = `b`.`business_id`  
				WHERE `b`.`business_id` = ' . $bus_id;
	
		$data = self::findBySql($sql)->createCommand()->queryOne();
		
		return $data == false ? '': $data;
	}
	
	function replaceInvoiceLog($invoice, $no = 1)
	{
		if(isset($invoice))
		{
			$sql = 'REPLACE INTO `invoice_log` (`business_id`, `period`, `count`) 
					VALUES ('. $invoice['business_id'] . ',\'' . $invoice['date']. '\',' . ($invoice['count'] + $no) . ')';
			
			if(self::findBySql($sql)->createCommand()->excute() !== false)
				return true;
		} else
			return false;
	}
}
