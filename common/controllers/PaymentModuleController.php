<?php

namespace common\controllers;

use common\helpers\Billsource;
use common\models\business\BusinessClient as BizClient;
use common\models\individual\IndividualClient as IndClient;
use common\models\invoice\Invoice;
use Yii;

class PaymentModuleController extends BaseController
{
    /**
     * Defines how certain actions can be executed irrespective of the default behavior
     *
     * @return array $actions defines actions behavior
     */
    public function actions()
    {
        return parent::actions();
    }

    /**
     * Defines behaviors to attach to actions in this class.
     *
     * @return array $behaviors an array of behaviors
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Handles beforeAction event trigger before controller actions are executed
     *
     * @param string $action current action being executed
     *
     * @return boolean true|false if action is executed successfully returns true otherwise false
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->initClient();

            return true;
        } else {
            return false;
        }
    }

    private function initClient()
    {
        if (is_null($this->client)) {
            if(!$this->user->identity->business_user) {
                $this->client = IndClient::findIdentity($this->userId);
            } else {
                $this->client = BizClient::findIdentity($this->userId);
            }
        }
        Yii::$app->params['client'] = $this->client;
    }

    protected function processPayment()
    {
        $orderNum = 0;
        $fees = 0;
        $invoiceToPay = array();
        $billsource = new Billsource;
        $invoice = new Invoice;
        $id_invoices = $this->session->get('invoice.ids');
        $ip = $this->request->getUserIP();

        foreach($id_invoices as $id_invoice) {
            $invoiceToPay[] = $invoice->findSelectedInvoice($id_invoice);
        }
        
        if(!empty($invoiceToPay)) {
            $fees = $billsource->startInvoicePaymentProcess($this->userId, 
                    $id_invoices, $invoiceToPay, $orderNum);
            
            $this->audit->log($this->userId, get_class($this), 'ConfirmBillPayment',
                    $this->isBusinessUser($this->user), $ip);
        }

        return array(
          'orderNum' => $orderNum,
          'items' => $invoiceToPay,
          'fees' => $fees
        );
    }
    
    private function isBusinessUser($user) 
    {
        $client = $user->identity;
        if($client->business_user) 
            $log = 'Business client bill payment';
        else 
            $log = 'Individual client bill payment';
        
        return $log;
    }
}