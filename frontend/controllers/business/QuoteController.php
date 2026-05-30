<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\events\BillEvent;
use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\catalog\Product;
use common\models\document\BillerDocumentFactory;
use common\models\invoice\InvoiceLineManager;
use common\models\invoice\InvoiceLog;
use common\models\invoice\Quote;
use common\models\invoice\QuoteSearch;
use common\models\Status;

/**
 * Controller class contining logic for all actions/process in quote model
 * 
 * @author Kenneth Onah
 *
 */
class QuoteController extends BusinessController
{	
	/**
	 * TODO
	 */
    public function actionCreate()
    {
    	$request = $this->request;
    	$session = $this->session;
    	$audit = $this->audit;
    	$ip_addr = $request->getUserIP();
        $docFactory = new BillerDocumentFactory;
    	$quote = $docFactory->makeQuote();
        $lineManager = new InvoiceLineManager($quote);
        $crm = new BusinessClientCrm;
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);
    	$customers = $crm->findAllByBusinessId($biller->id);

    	if($request->isPost) {
    		if(!InvoiceLog::canCreateBill($biller->id)) {
    			$session->setFlash('error', 'You have reached your maximum number of quotes for the month! Please contact customer care');
    			return $this->redirect(['index']);
    		}
    		$quote->load($request->post(), 'Quote');
            $quote->business_id = $biller->id;

			$this->manageCustomer($request->post('customer-select'), $quote, $biller);
            if($quote->validate() &&  $quote->save(false))
            {
                $lineManager->manage($quote, $request->Post('InvoiceLine'));
                if($lineManager->validateLineItems() && $lineManager->saveLineItems($quote)) {
                	InvoiceLog::replaceClientInvoiceLog($biller->id);
                    $audit->log($this->userId, get_class($this), 'NewQuote', 'Successfully sent new quote.', $ip_addr);
                	$session->setFlash('success', 'Quote created and saved successfully');
                    $event = new BillEvent();
                    $event->biller = $biller;
                    $event->audit = $audit;
                    $quote->trigger(BillEvent::BILL_NEW, $event);
                	return $this->redirect(['index']);
                } else {
                    $audit->log($this->userId, get_class($this), 'NewQuote', 'Encountered an error sending new quote', $ip_addr);
                }
            }
    	}
    	
        $quote->marketing = $biller->marketing_message;
    	$quote->reference_number = $this->referenceNumberGenerator();
        return $this->render('create', [
        	'biller'		=> $biller,
        	'customers'		=> $customers,
        	'quote' 		=> $quote,
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
        $quote = Quote::findOne($id);
    	if(isset($quote) && $quote) {
    		$quote->deleted = Quote::DELETED;
    		$quote->save(false);
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
        $quote = Quote::findOne($id);
    	$lineManager = new InvoiceLineManager($quote);
    	$crm = new BusinessClientCrm;
    	$biller = BusinessClient::findOne(['user_id' => $this->userId]);
    	$customers = $crm->findAllByBusinessId($biller->id);
    	$statuses = Status::findAllStatuses();
    	
    	if($this->request->isPost) {
    		$quote->load($this->request->Post(), 'Quote');
    	
    		if($quote->validate() &&  $quote->save(false))
    		{
    			$lineManager->manage($quote, $this->request->Post('InvoiceLine'));
    			$lineManager->validate();
    			$lineManager->saveLineItems($quote);
    			$this->session->setFlash('success', 'Invoice update successfully');
    			$this->redirect(['index']);
    		}
    	}
    	
        return $this->render('edit', [
        	'biller'		=> $biller,
        	'customers'		=> $customers,
        	'quote' 		=> $quote,
        	'lineManager'	=> $lineManager,
        	'statuses'		=> $statuses,
			'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
     * Renders debtors page.
     *
     * @return string home page view script.
     */
    public function actionIndex()
    {
    	$searchModel = new QuoteSearch();
    	$dataProvider = $searchModel->search($this->client, $this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionReceived()
    {
    	$status = Quote::STATUS_SENT;
    	$user_id = $this->userId;
    	$request = $this->request;
    	$searchModel = new QuoteSearch();
    	$dataProvider = $searchModel->searchQuotesForBusiness($user_id, $request->queryParams, $status);
    	
    	$this->audit->log($this->userId, get_class($this), 'ManageCreditorQuotes',
    			'Maintain quotes from creditors', $request->getUserIP());
    	
    	return $this->render('received', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
    public function actionRejected()
    {
    	$status = Quote::STATUS_REJECTED;
    	$user_id = $this->userId;
    	$request = $this->request;
    	$searchModel = new QuoteSearch();
    	$dataProvider = $searchModel->searchQuotesForBusiness($user_id, $request->queryParams, $status);
    	 
    	$this->audit->log($this->userId, get_class($this), 'ManageCreditorQuotes',
    			'Maintain quotes from creditors', $request->getUserIP());
    	 
    	return $this->render('rejected', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
}
