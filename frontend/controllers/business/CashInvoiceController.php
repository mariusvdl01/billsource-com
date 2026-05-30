<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\models\business\BusinessClient;
use common\models\catalog\Product;
use common\models\document\BillerDocumentFactory;
use common\models\invoice\CashInvoice;
use common\models\invoice\InvoiceLineManager;
use common\models\invoice\InvoiceLog;
use common\models\invoice\InvoiceSearch;
use common\models\Status;
use yii\web\Response;

/**
 * Controller class containing logic for all actions/process in invoice model
 * 
 * @author Kenneth Onah
 *
 */
class CashInvoiceController extends BusinessController
{	
	public $defaultAction = 'paid';

	/**
	 * TODO
	 */
    public function actionCreate()
    {  
    	$audit = $this->audit;
        $request = $this->request;
    	$ip = $request->getUserIP();
        $invoice = (new BillerDocumentFactory())->makeCashInvoice();
        $lineManager = new InvoiceLineManager($invoice);
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);
    	
    	if($request->isPost) {
    		if(!InvoiceLog::canCreateBill($biller->id)) {
    			$this->session->setFlash('error', 'You have reached your maximum number of bills for the month! Please contact customer care');
    			return $this->redirect(['/business/invoice/paid']);
    		}
    		$invoice->load($request->Post(), 'CashInvoice');
            $invoice->business_id = $biller->id;
            $invoice->status_id = CashInvoice::STATUS_PAID;

            if($invoice->validate() &&  $invoice->save(false))
            {
                $lineManager->manage($invoice, $request->post('InvoiceLine'));
                if($lineManager->validateLineItems() && $lineManager->saveLineItems($invoice)) {
                	InvoiceLog::replaceClientInvoiceLog($biller->id);
                    $audit->log($this->userId, $this->action->uniqueId, 'NewCashInvoice', 'Successfully created new cash invoice', $ip);
                	$this->session->setFlash('success', 'Invoice created and saved successfully');
                	return $this->redirect(['/business/invoice/paid']);
                }
            }
    	} 
    	
  		$invoice->reference_number = $this->referenceNumberGenerator();
        return $this->render('create', [
        	'biller'		=> $biller,
        	//'customers'		=> $customers,
        	'invoice' 		=> $invoice,
        	'lineManager'	=> $lineManager,
			'products'		=> Product::findAvailableProduct($this->client)
        ]);
    }

    /**
	 * TODO
	 */
    public function actionDelete($id)
    {
    	$invoice = CashInvoice::findOne($id);
    	if(isset($invoice) && $invoice) {
    		$invoice->deleted = CashInvoice::DELETED;
    		$invoice->save(false);
    		$this->session->setFlash('success', 'Cash invoice deleted successfully');
    	} else {
    		$this->session->setFlash('error', 'Cash invoice could not be deleted');
    	}
    	return $this->redirect(['/business/invoice/paid']);
    }

    /**
	 * TODO
	 */
    public function actionUpdate($id)
    {
    	$invoice = CashInvoice::findOne($id);
    	$lineManager = new InvoiceLineManager($invoice);
    	//$crm = new BusinessClientCrm;
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);
    	//$customers = $crm->findAllByBusinessId($biller->business_id);
    	$statuses = Status::findAllStatuses();
    	
    	if($this->request->isPost) {
    		$invoice->load($this->request->Post(), 'CashInvoice');
    		if($invoice->validate())
    		{
				if($invoice->status_id == CashInvoice::STATUS_PAID) {
					$invoice->paid = CashInvoice::INVOICE_PAID;
					$invoice->savePaymentInfo();
				}
				$invoice->save(false);
    			$lineManager->manage($invoice, $this->request->Post('InvoiceLine'));
    			$lineManager->validate();
    			$lineManager->saveLineItems($invoice);

    			$this->session->setFlash('success', 'Cash invoice updated successfully');
    			return $this->redirect(['/business/invoice/paid']);
    		}
    	}
    	
        return $this->render('edit', [
        	'biller'		=> $biller,
        	//'customers'		=> $customers,
        	'invoice' 		=> $invoice,
        	'lineManager'	=> $lineManager,
        	'statuses'		=> $statuses,
			'products'		=> Product::findAvailableProduct($this->client)
        ]);
    }

    /**
     * Renders debtors page.
     *
     * @return Response
     */
    public function actionIndex()
    {
    	return $this->redirect(['/business/invoice/paid']);
    }

	public function actionRefund()
	{
		$searchModel = new InvoiceSearch();
		$dataProvider = $searchModel->searchRefunded($this->userId, $this->request->queryParams);
		return $this->render('refunded', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}
