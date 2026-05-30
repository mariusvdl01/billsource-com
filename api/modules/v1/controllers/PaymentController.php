<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/13/15
 * Time: 7:17 PM
 */

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\User;
use api\modules\v1\models\Invoice;
use common\helpers\Billsource;

class PaymentController extends AbstractBaseController
{
    public $modelClass = 'api\modules\v1\models\Invoice';

    public function actionMethods()
    {
        $request = $this->request;
        $token = $request->get()['token'];
        $user = User::findIdentityByAccessToken($token);

        if(empty($user)) {
            return array('message' => 'Invalid request');
        } else {
            $this->audit->log($user->id, get_class($this), 'ManageCreditorBills',
                'Manage bills from creditors - Mobile', $request->getUserIP());

            return Yii::$app->getModule('payment')->getPaymentPlugins();
        }
    }

    public function actionSubmit()
    {
        $request = $this->request;
        if($request->isPost) {
            $post = json_decode(file_get_contents("php://input"), true);
            $user = User::findIdentityByAccessToken($post["token"]);
            if(empty($user)) {
                return [
                    'message' => 'Invalid user request',
                ];
            }
            if($post["data"]["id"] !== null) {
                $paymentData = $this->processPayment($user, $post["data"]["id"], $post["data"]["method"]);
                $paymentData['method'] = $post["data"]["method"];

                return $paymentData;
            }
        }

        return [
            'message' => 'Invalid request performed',
        ]; 
    }

    public function actionSetTransaction()
    {
        $request = $this->request;
        if($request->isPost) {
            $post = json_decode(file_get_contents("php://input"), true);
            $user = User::findIdentityByAccessToken($post["token"]);
            if(empty($user)) {
                return [
                    'message' => 'Invalid user request',
                ];
            }

            $gateway = Yii::$app->getModule($post["data"]["method"]);
            $gateway->setTransactionData($post["data"]);
            $response = $gateway->doSetTransaction();
            
            if(!$response->successful) {
                return [
                    'message' => 'Error code: ' . $response->resultCode . ', Error message: ' . $response->resultMessage,
                ];
            } else {
                return ['url' => $gateway->getRedirectUrl() . '?PayUReference=' . $response->payUReference];
            }
        }

        return [
            'message' => 'Invalid payment request performed',
        ];
    }

    protected function processPayment($user, $invoiceId, $paymentMethod)
    {
        $orderNum = 0;
        $fees = 0;
        $userId = $user->id;
        $invoiceToPay = array();
        $billsource = new Billsource;
        $invoice = new Invoice;
        $id_invoices = array($invoiceId);
        $ip = $this->request->getUserIP();

        foreach($id_invoices as $id_invoice) {
            $invoiceToPay[] = $invoice->findSelectedInvoice($id_invoice);
        }
        
        if(!empty($invoiceToPay)) {
            $fees = $billsource->startInvoicePaymentProcess($userId, $id_invoices, $invoiceToPay, $orderNum);
            $this->audit->log($userId, get_class($this), 'ConfirmBillPayment - Mobile', $userId, $ip);
        }

        return array(
          'orderNum' => $orderNum,
          'items' => $invoiceToPay,
          'fees' => $fees
        );
    }
}