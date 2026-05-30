<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\models\invoice\Invoice;
use common\models\invoice\InvoiceSearch;

/**
 * Controller class contining logic for all actions/process in invoice model
 * 
 * @author Kenneth Onah
 *
 */
class CreditorController extends BusinessController
{
	/**
	 * Renders all debtors page.
	 *
	 * @return string $view home page view script.
	 */
	public function actionIndex()
	{
		$searchModel = new InvoiceSearch();
		$dataProvider = $searchModel->searchAllCreditors($this->userId, $this->request->queryParams);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

    /**
     * Renders debtors page.
     *
     * @return string $view home page view script.
     */
    public function actionPaid()
    {
        $status = Invoice::INVOICE_PAID;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchCreditorsByPaymentStatus($this->userId, $this->request->queryParams, $status);
        return $this->render('paid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionUnpaid()
    {
    	$ip = $this->request->getUserIP();
    	$user_id = $this->userId;
    	$request = $this->request;
        $status = Invoice::INVOICE_UNPAID;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchCreditorsByPaymentStatus($user_id, $request->queryParams, $status);
    	$oldest = $searchModel->findOldestBusinessBillByCreditor($user_id);
    	$header = $searchModel->findInvoiceForHeader($user_id);
    	
    	if($request->isPost) {
            $this->session->remove('invoice.ids');
    		if($request->post('status') !== null) {
                $this->audit->log($this->userId, get_class($this), 'ManageCreditorBills', 
                'Manage bills from creditors', $ip);
    			$this->session->set('invoice.ids', $request->post('status'));
    			return $this->redirect('/payment');	
    		}
    		$this->session->setFlash('error', 'Select at least one invoice to pay');
    		return $this->refresh();
    	}
    	
    	return $this->render('unpaid', [
    		'oldest' => $oldest,
    		'header' => $header,
    		'searchModel' => $searchModel,
    		'dataProvider' => $dataProvider,
    	]);
    }
    
    
}
