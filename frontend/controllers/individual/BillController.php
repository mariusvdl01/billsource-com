<?php

namespace frontend\controllers\individual;

use common\models\invoice\Invoice;
use common\models\invoice\InvoiceSearch;

/**
 * The controller class for individual authenticated users.
 * 
 * @author Kenneth Onah
 *
 */
class BillController extends \common\controllers\IndividualController
{
	public function actions()
	{
		return parent::actions();
	}

	/**
	 * Renders unpaid invoices.
	 *
	 */
    public function actionUnpaid()
    {
		$user_id = $this->userId;
		$request = $this->request;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchForIndividual($user_id, $request->queryParams);
    	$ip = $this->request->getUserIp();
    	$this->audit->log(
    	    $user_id,
            $this->action->uniqueId,
            'ManageBills',
            'Individual bills maintenance',
            $ip
        );
    	 
    	if($request->isPost) {
    		if($request->post('status') !== null) {
    			$this->session['invoice.ids'] = $request->post('status');
    			return $this->redirect(['/payment']);
    		}
    		$this->session->setFlash('error', 'Select at least one invoice to pay');
    		return $this->refresh();
    	}
    	return $this->render('unpaid', [
    		'searchModel' => $searchModel,
    		'dataProvider' => $dataProvider,
    	]);    	
    }
    
    /**
     * Renders paid invoices.
     *
     */
    public function actionPaid()
    {
        $paid = Invoice::INVOICE_PAID;
        $status = Invoice::STATUS_PAID;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchForIndividual(
    	    $this->userId,
            $this->request->queryParams,
            $paid,
            $status
        );
    	return $this->render('paid', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }

    /**
     * Renders pending invoices.
     *
     */
    public function actionPending()
    {
        $paid = Invoice::INVOICE_UNPAID;
        $status = Invoice::STATUS_PENDING;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->searchForIndividual(
            $this->userId,
            $this->request->queryParams,
            $paid,
            $status
        );
        return $this->render('pending', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Renders refunded invoices.
     *
     */
    public function actionRefund()
    {
        $paid = Invoice::INVOICE_UNPAID;
        $status = Invoice::STATUS_REFUND;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->searchForIndividual(
            $this->userId,
            $this->request->queryParams,
            $paid,
            $status
        );
        return $this->render('refund', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Renders disputed invoices.
     *
     */
    public function actionDispute()
    {
        $paid = Invoice::INVOICE_UNPAID;
        $status = Invoice::STATUS_DISPUTED;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->searchForIndividual(
            $this->userId,
            $this->request->queryParams,
            $paid,
            $status
        );
        return $this->render('dispute', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
