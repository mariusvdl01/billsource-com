<?php

namespace common\modules\payu;

use common\helpers\ArrayHelper;
use common\models\invoice\Invoice;
use common\models\payment\PaymentFees;
use Yii;
use yii\db\Command;

/**
 * payu-mea module definition class
 */
class PayUMEA extends \yii\base\Module
{
    protected $payuApi;
    protected $command;
    protected $order;
    protected $soapClient;

    public $apiVersion = 'ONE_ZERO';
    public $safeKey;
    public $apiUsername;
    public $apiPassword;
    public $transactionType;
    public $checkoutMode;
    public $paymentMethods;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\payu\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        if(is_null($this->command)) {
            $this->command = new Command();
            $this->command->db = Yii::$app->db;
        }
        if(is_null($this->soapClient)) {
            $this->payuApi = $this->getSoapApiClient();
        }
    }

    public function getReturnUrl()
    {
        return Yii::$app->request->getHostInfo() . '/payumea/validation';
    }

    public function getIpnUrl()
    {
        return Yii::$app->request->getHostInfo() . '/payumea/ipn';
    }

    public function setTransactionData($post)
    {
        $this->order = $post;
    }
    
    public function doSetTransaction()
    {
        $data = $this->prepareRequest();
        $response = $this->payuApi->setTransaction($data);
        $response = json_decode(json_encode($response));

        return $response->return;
    }

    public function doGetTransaction($reference)
    {
        $request = array(
            'Api' => 'ONE_ZERO',
            'Safekey' => trim($this->safeKey),
            'AdditionalInformation' => array(
                'payUReference' => $reference
            )
        );

        $response = $this->getSoapApiClient()->getTransaction($request);

        $response = json_decode(json_encode($response));

        return $response->return;
    }

    public function getRedirectUrl() 
    {
        if ($this->checkoutMode == 'LIVE')
            return 'https://secure.payu.co.za/rpp.do';
        else
            return 'https://staging.payu.co.za/rpp.do';
    }

    protected function getWebServiceUrl() 
    {
        if ($this->checkoutMode == 'LIVE')
            return 'https://secure.payu.co.za/service/PayUAPI';
        else
            return 'https://staging.payu.co.za/service/PayUAPI';
    }

    /**
     * Returns an array of possible currency codes.
     */
    protected function supportedCurrencies() 
    {
      return array('NGN', 'ZAR');
    }

    protected function getSoapHeader()
    {   
        $header  = '<wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">';
        $header .= '<wsse:UsernameToken wsu:Id="UsernameToken-9" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">';
        $header .= '<wsse:Username>' . $this->apiUsername . '</wsse:Username>';
        $header .= '<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'. $this->apiPassword . '</wsse:Password>';
        $header .= '</wsse:UsernameToken>';
        $header .= '</wsse:Security>';

        return $header;
    }

    protected function getSoapApiClient()
    {
        $ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
        $header = $this->getSoapHeader();
        $soapWsdlUrl = $this->getWebServiceUrl().'?wsdl';
        $headerbody = new \SoapVar($header, XSD_ANYXML, null, null, null);
        $soapHeader = new \SOAPHeader($ns, 'Security', $headerbody, true);

        $soapClient = new \SoapClient($soapWsdlUrl, array('trace' => 1, 'exception' => 0));
        $soapClient->__setSoapHeaders($soapHeader);
  
        return $soapClient;
    }

    public function processTransaction($response, $user)
    {
        $valid = false;
        $client = $user->identity;
        $clientType = 'IND';
        if($client->business_user)
            $clientType = 'BIZ';

        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $command = $this->command;
        $paid = $response->transactionState == 'SUCCESSFUL' ? '1' : '0';
        $orderNum = $response->merchantReference;
        $result = $this->validateResponse($response, $orderNum, $paid);
        $data = [
          'paid' => $paid,
          'response_time' => $now,
          'id' => $orderNum
        ];

        $command->update('receipt', $data, "id = $orderNum")->execute();
        if(isset($response) && isset($orderNum))
        {
            $data = [
              'payment_result' => $paid,
            ];
            $command->update('invoice_payment', $data, "pay_index = $orderNum")->execute();

            if($paid == '1')
            {
                $sql = 'UPDATE ' . Invoice::tableName() . ' SET `paid` = ' . Invoice::INVOICE_PAID .
                    ', `status_id` = ' . Invoice::STATUS_PAID . ' 
                        WHERE `id` IN  
                        (SELECT `invoice_id` 
                        FROM `invoice_payment` 
                        WHERE `payment_result` = 1 
                        AND `pay_index` = ' . $orderNum . ')';

                $command->setSql($sql)->execute();

                $sql = 'UPDATE `payment_fees` SET `fee_paid` = ' . PaymentFees::TRANX_FEE_PAID
                  .' WHERE `payment_index` = :num';
                $command->setSql($sql)->bindValue(':num', $orderNum)->execute();

                $sql = "INSERT INTO `reward` (`reference_type`, `reference_id`, `description`, `amount`, `created_at`)
                        SELECT '$clientType', `a`.`user_id` , 'Payment rewards', FLOOR(SUM(`payment_amount`)) `inv_total`, CURRENT_TIMESTAMP
                        FROM `invoice_payment` `a` 
                        INNER JOIN `user` `u` ON `u`.`id` = `a`.`user_id` 
                        WHERE `payment_result` = 0 
                        AND `pay_index` = $orderNum
                        GROUP BY `payment_reference`, `a`.`user_id` LIMIT 0, 1 ";
                $command->setSql($sql)->execute();
                // update rewards
                if(!$client->business_user) {
                    $sql = "SELECT SUM(`amount`) `rewards` 
                            FROM `reward` 
                            WHERE `reference_type` = '$clientType' 
                            AND `reference_id` = (SELECT `id` FROM `user` WHERE `id` = :id)";

                    $data = $command->setSql($sql)->bindValue(':id', $client->id)->queryAll();

                    if(!empty($data['rewards'])) {
                        $row = [];
                        ArrayHelper::recursive($data, $row);

                        $sql = 'UPDATE ';
                        if($clientType == 'IND')
                            $sql .= '`individual_client` ';
                        else
                            $sql .= '`business_client` ';

                        $sql .= 'SET `rewards` = ' . $row['rewards'] . ' 
                                WHERE `user_id` = ' . $client->id;
                        $command->setSql($sql)->execute();
                    }
                }
                $valid = true;
            }
        }
        return array('valid' => $valid, 'result' => $result);
    }

    protected function validateResponse($response, $orderNum)
    {
        $messages = '';

        if(!isset($response))
            $messages[] = 'Invalid PayU response. No response posted.';
        if(!isset($orderNum))
            $messages[] = 'Invalid invoice number ID';

        return $messages;
    }

    protected function prepareRequest()
    {
        $order = $this->order;
        //var_dump($order);exit;
        $data = array(
            'Api' => trim($this->apiVersion),
            'Safekey' => trim($this->safeKey),
            'TransactionType' => trim($this->transactionType),
            'AdditionalInformation' => [
                'merchantReference' => $order['orderNum'],
                'supportedPaymentMethods' => $this->paymentMethods,
                'demoMode' => $this->checkoutMode == 'LIVE' ? 'false' : 'true',
                'secure3d' =>  'true',
                'returnUrl' => $this->getReturnUrl(),
                'cancelUrl' => $this->getReturnUrl(),
            ],
            //Customer details
            'Customer' => array(
                'ip' => Yii::$app->request->getUserIP()
            ),
            // Cart details
            'Basket' => array(
                'description' => 'Bill(s) payment',
                'amountInCents' => (int) ($order['total'] * 100),
                'currencyCode' => 'ZAR'
            ),
        );

        return $data;
    }
}
