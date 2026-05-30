<?php 

namespace common\models\business;

use Yii;

class Reader extends BusinessClient {
	
	protected $_permittedRoles = [
		'reader'
	];
	
	public function canCreateInvoice($data)
	{
		if(isset($data['maximum_limit_invoices']) && $data['count'] >= $data['maximum_limit_invoices'] )
			return 'Maximuim number of invoices captured for the month.';
	}
}
