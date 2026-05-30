<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\events\BillEvent;
use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\catalog\Product;
use common\models\document\BillerDocumentFactory;
use common\models\invoice\Invoice;
use common\models\invoice\InvoiceLineManager;
use common\models\invoice\InvoiceLog;
use common\models\invoice\InvoiceSearch;
use common\models\Status;

/**
 * Controller class containing logic for all actions/process in invoice model
 * 
 * @author Kenneth Onah
 *
 */
class InvoiceController extends BusinessController
{	
	public $defaultAction = 'unpaid';

	/**
	 * TODO
	 */
    public function actionCreate()
    {  
    	$audit = $this->audit;
        $request = $this->request;
    	$ip = $request->getUserIP();
        $docFactory = new BillerDocumentFactory;
        $invoice = $docFactory->makeInvoice();
        $lineManager = new InvoiceLineManager($invoice);
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);
    	$customers = (new BusinessClientCrm())->findAllByBusinessId($biller->id);
    	
    	if($request->isPost) {
    		if(!InvoiceLog::canCreateBill($biller->id)) {
    			$this->session->setFlash('error', 'You have reached your maximum number of bills for the month! Please contact customer care');
    			return $this->redirect(['index']);
    		}
    		$invoice->load($request->Post(), 'Invoice');
            $invoice->business_id = $biller->id;

            $this->manageCustomer($request->post('customer-select'), $invoice, $biller);
            if($invoice->validate() &&  $invoice->save(false))
            {
                $lineManager->manage($invoice, $request->post('InvoiceLine'));
                if($lineManager->validateLineItems() && $lineManager->saveLineItems($invoice)) {
                	InvoiceLog::replaceClientInvoiceLog($biller->id);
                    $audit->log($this->userId, $this->action->uniqueId, 'NewInvoice', 'Successfully sent new invoice', $ip);
                	$this->session->setFlash('success', 'Invoice created and saved successfully');
                    $event = new BillEvent();
                    $event->biller = $biller;
                    $event->audit = $audit;
                    $invoice->trigger(BillEvent::BILL_NEW, $event);
                	return $this->redirect(['index']);
                } else {
                    $audit->log($this->userId, $this->action->uniqueId, 'NewInvoice', 'Error while sending new invoice', $ip);
                }
            }
    	}

        $invoice->marketing = $biller->marketing_message;
  		$invoice->reference_number = $this->referenceNumberGenerator();
        return $this->render('create', [
        	'biller'		=> $biller,
        	'customers'		=> $customers,
        	'invoice' 		=> $invoice,
        	'lineManager'	=> $lineManager,
			'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
	 * TODO
	 */
    public function actionDelete($id)
    {
    	$invoice = Invoice::findOne($id);
    	if(isset($invoice) && $invoice) {
    		$invoice->deleted = Invoice::DELETED;
    		$invoice->save(false);
    		$this->session->setFlash('success', 'Invoice deleted successfully');
    	} else {
    		$this->session->setFlash('error', 'Invoice could not be deleted');
    	}
    	return $this->redirect(['index']);
    }

    /**
	 * TODO
	 */
    public function actionUpdate($id)
    {
    	$invoice = Invoice::findOne($id);
    	$lineManager = new InvoiceLineManager($invoice);
    	$crm = new BusinessClientCrm;
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);
    	$customers = $crm->findAllByBusinessId($biller->id);
    	$statuses = Status::findAllStatuses();
    	
    	if($this->request->isPost) {
    		$invoice->load($this->request->Post(), 'Invoice');
    		if($invoice->validate())
    		{
				if($invoice->status_id == Invoice::STATUS_PAID) {
					$invoice->paid = Invoice::INVOICE_PAID;
					$invoice->savePaymentInfo();
				}
				$invoice->save(false);
    			$lineManager->manage($invoice, $this->request->Post('InvoiceLine'));
    			$lineManager->validate();
    			$lineManager->saveLineItems($invoice);

    			$this->session->setFlash('success', 'Invoice updated successfully');
    			return $this->redirect(['index']);
    		}
    	}
    	
        return $this->render('edit', [
        	'biller'		=> $biller,
        	'customers'		=> $customers,
        	'invoice' 		=> $invoice,
        	'lineManager'	=> $lineManager,
        	'statuses'		=> $statuses,
			'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
     * Renders debtors page.
     *
     */
    public function actionIndex()
    {
    	return $this->redirect(['unpaid']);
    }
    
    public function actionPaid() 
    {
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchPaid($this->client, $this->request->queryParams);
    	return $this->render('paid', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }

    public function actionUnpaid() 
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->client, $this->request->queryParams);
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

	public function actionRefund()
	{
        $status = Invoice::STATUS_REFUND;
		$searchModel = new InvoiceSearch();
		$dataProvider = $searchModel->searchByState($this->client, $this->request->queryParams, $status);
		return $this->render('refunded', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
    
    public function actionDisputed()
    {
        $status = Invoice::STATUS_DISPUTED;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchByState($this->client, $this->request->queryParams, $status);
    	return $this->render('disputed', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
    public function actionPending()
    {
        $status = Invoice::STATUS_PENDING;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchByState($this->client, $this->request->queryParams, $status);
    	return $this->render('pending', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
    public function actionDebtor()
    {
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchAllDebtors($this->userId, $this->request->queryParams);
        return $this->render('debtor', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
