<?php
/**
 * File Paypal.php.
 *
 * @author Marcio Camello <marciocamello@outlook.com>
 * @see https://github.com/paypal/rest-api-sdk-php/blob/master/sample/
 * @see https://developer.paypal.com/webapps/developer/applications/accounts
 */

namespace common\components;

use PayPal\Api\PaymentExecution;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Component;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;

class Paypal extends Component
{
    protected $requestData;
    protected $apiContext;
    
    public function init()
    {
        parent::init();

        $apiContext = new ApiContext(
          new \PayPal\Auth\OAuthTokenCredential(
            'AcxIW_zi3Foc7B5GIgZArYELo1QV8nTO4nLrxashZd4phav3-pbnDzyiwcZrj73jvyvgSjj6vZMWNmVw',     // ClientID
            'EJ7R3DoKuNVAuk9kWozo9Vs0aXe0pmUZ30iWzuGak3SwtHGwFrjB0l3PEx4JJFGNKzybvMW6HorX4ZEs'      // ClientSecret
          )
        );
        
        $this->apiContext = $apiContext;
    }

    public function setECRequestData($data)
    {
        $this->requestData = $data;

        return $this;
    }

    public function doExpressCheckout()
    {
        

        //SAMPLE 3
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $items = $this->getItems();
        $itemList = new ItemList();
        $itemList->setItems($items);

        //$details = $this->getAdditionalPaymentDetails();
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
          ->setTotal($this->requestData['total']);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
          ->setItemList($itemList)
          ->setDescription("Billsource Bill payment")
          ->setInvoiceNumber($this->requestData['orderNum']);

        // ### Redirect urls
        // Set the urls that the buyer must be redirected to after 
        // payment approval/ cancellation.
        //$baseUrl = getBaseUrl();
        $returnUrl = $this->getRedirectUrl();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl . "?success=true")
          ->setCancelUrl($returnUrl . "?success=false");
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
          ->setPayer($payer)
          ->setRedirectUrls($redirectUrls)
          ->setTransactions(array($transaction));

        // ### Create Payment
        // Create a payment by calling the 'create' method
        // passing it a valid apiContext.
        // (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state and the
        // url to which the buyer must be redirected to
        // for payment approval
        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            //$request = clone $payment;
            //\ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
        }

        return $payment;
    }

    protected function getItems()
    {
        $data = $this->requestData;
        $items = array();
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information

        for ($i = 1; $i < count($data); $i++) {
            if(array_key_exists('itemRef'.$i, $data)
              || array_key_exists('itemAmount'.$i, $data)
            ) {
                $item = new Item();
                $item->setName($data['itemRef'.$i])
                  ->setCurrency('USD')
                  ->setQuantity(1)
                  ->setPrice(number_format($data['itemAmount'.$i], 2, '.', ''));
                $items[] = $item;
            }
        }

        return $items;
    }

    public function doExecuteExpressCheckout($paymentId, $payerId)
    {
        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        try {
            $result = $payment->execute($execution, $this->apiContext);
            $payment = Payment::get($paymentId, $this->apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
        }

        return $payment;
    }

    protected function getRedirectUrl()
    {
        $paypalModule = Yii::$app->getModule('paypal');
        if($paypalModule) {
            $returnUrl = $paypalModule->getReturnUrl();
        } else {
            throw new InvalidConfigException('Invalid Paypal module configuration');
        }

        return $returnUrl;
    }
}