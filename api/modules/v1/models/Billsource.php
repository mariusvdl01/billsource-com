<?php

namespace api\modules\v1\models;

use common\models\invoice\Invoice;
use common\models\business\BusinessClient;
use common\models\invoice\InvoiceLineManager;

class Billsource extends \common\helpers\Billsource
{
	public function loadInvoice($invoice_id)
    {
        $invoice = Invoice::find()
                    //->joinWith('invoiceAgeType', true, 'INNER JOIN')
                    ->where('[[id]]=:id', [':id'=>$invoice_id])
                    //->andWhere('age_paid = 0 
                    //    AND DATEDIFF(NOW(), due_date) >= minimum_days
                    //    AND DATEDIFF(NOW(), due_date) <= maximum_days')
                    ->one();
        $lineManager = new InvoiceLineManager($invoice);
        $biller = BusinessClient::find()
                    ->joinWith('province', true, 'INNER JOIN')
                    ->where('[[business_client.id]]=:id', [':id'=>$invoice->business_id])
                    ->one();    
        return ['invoice' => $invoice, 'lines' => $lineManager->getItems(), 'biller' => $biller];
    }
}
