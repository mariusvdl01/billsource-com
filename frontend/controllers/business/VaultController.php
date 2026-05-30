<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\models\business\BusinessClient;
use common\models\invoice\Invoice;

/**
 * Controller class contining logic for all actions/process in invoice module
 * 
 * @author Kenneth Onah
 *
 */
class VaultController extends BusinessController
{	
	public $defaultAction = 'create';
	/**
	 * TODO
	 */
    public function actionCreate()
    {    	
        $invoice = new Invoice();
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);

    	if($invoice->load($this->request->Post())) {
            $invoice->business_id = $biller->id;
            $invoice->paid = 0;

            if($invoice->validate() &&  $invoice->save(false))
            {
                $this->audit->log($this->userId, get_class($this), __METHOD__,
                		'Successfully saved invoice in vault.', $this->request->getUserIP());

                // Redirect to wherever you want
                $this->session->setFlash('success', 'Invoice saved in vault successfully');
                return $this->refresh();
            }
            $this->session->setFlash('error', 'Server error encountered while saving invoice in vault');
    	}
  		
        return $this->render('create', [
        	'biller'		=> $biller,
        	'invoice' 		=> $invoice,
        ]);
    }
}
