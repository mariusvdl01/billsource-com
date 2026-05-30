<?php

namespace common\modules\sms;

use Yii;

/**
 * SMS Gateway model class. This module enable sending of sms through an SMS gateway 
 * 
 * @author Kenneth Onah
 *
 */
class SmsGateway extends \yii\base\Module
{
    /**
     * @var \SOAPClient SOAP client
     */
    protected static $soapClient = null;

	/**
	 * This property is read-only
     *
	 * @var string $controllerNamespace the namespace for controllers in this model
	 */
    public $controllerNamespace = 'common\modules\sms\controllers';
    //protected $ipAddress = '41.160.154.77';

    /**
     * @var array default HTTP headers
     */
    protected $defaultHeaders = array(
        'user_agent' => 'Billsource/2.0',
        'connect_timeout' => 30,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'cache_ttl' => 86400,
        'trace' => true,
        'exceptions' => true,
    );

    /**
     * $This property is read-only
     *
     * @var string SOAP service WSDL endpoint
     */
    protected $url = 'http://www.mobisysservices.co.za:49335/SendSmsMessageSoapService?wsdl';

    /**
     * @var resource options array to pass headers to SOAP request
     */
    protected $streamContext;

    /**
     * Initializes the module
     * 
     */
    public function init()
    {
        parent::init();

        $this->streamContext = stream_context_create();

        // Create the stream_context and add it to the options
        $options = array(array_merge($this->defaultHeaders, array('stream_context' => $this->streamContext)));

        // Create new SOAP client
        if (null === self::$soapClient) {
            self::$soapClient = new \SoapClient($this->url, $options);
        }
    }
    
    /**
     * Send sms through the SMS Gateway
     *
     * @param string $cellNumber phone number
     * @param string $message message to send
     * @param string $transactionId unique message identifier
     * @param array $httpHeaders HTTP request headers
     *
     * @return boolean if sms is sent successfully or otherwise
     */
    public function sendSms($cellNumber, $message, $transactionId, $httpHeaders = [])
    {
    	try {
            $this->setHttpHeader($httpHeaders);
            $payload = $this->prepareMessageRequest($cellNumber, $message, $transactionId);
            return $this->doAction($payload);
    	} catch (\Exception $e) {
    	    \Yii::trace($e->getMessage(), 'error');
    		return false;
    	}
    }

    /**
     * Set HTTP headers passed to the request
     *
     * @param array $httpHeaders
     */
    private function setHttpHeader($httpHeaders)
    {
        stream_context_set_option($this->streamContext, array('http' => array('header' => $httpHeaders)));
    }

    private function prepareMessageRequest($number, $message, $uuid)
    {
        return [
            'SendSmsMessageRequest' => [
                'SmsMessages' => [
                    'SmsMessage' => [
                        'MessageContents' => $message,
                        'RecipientMSISDN' => $number,
                        'SmsID' => $uuid
                    ],
                ],
                'ClientAccount' => [
                    'Password' => Yii::$app->params['smsPassword'],
                    'UserName' => Yii::$app->params['smsUsername']
                ],
            ],
        ];
    }

    /**
     * Execute SOAP method on the client
     *
     * @param string $payload the payment transaction details
     *
     * @return boolean result of request
     */
    private function doAction($payload)
    {
        $response = self::$soapClient->SendSmsMessage($payload);
        $response = json_decode(json_encode($response));
        $result = $this->processResponse($response);

        return $result;
    }

    /**
     * Process message request
     *
     * @param \stdClass $response message response
     *
     * @return boolean
     */
    private function processResponse($response)
    {
        if ($response->SendSmsMessageResult->Status) {
            return true;
        }

        return false;
    }

    /**
     * Prepares message payload
     *
     * @deprecated since 1.0
     * @param string $cellNumber phone number
     * @param string $message message to send
     * @param string $transactionId unique message identifier
     *
     * @return array
     */
    protected function prepare($cellNumber, $message, $transactionId)
    {
    	$postdata = http_build_query(
    		[
                'RecipientMSISDN' => $cellNumber,
                'MessageContent' => $message,
                'UserName' => '',
                'PassWord' => '',
                'TransactionID' => $transactionId        //'2C92FCC6-0238-4581-95B5-FE4AAB72B123'
    		]
    	);
        //return $postdata;
        
    	$opts = [
    		'http' => [
    			'method'  => 'POST',
                'header' => "User-Agent:Billsource/2.0\r\n" . 'Content-type: application/x-www-form-urlencoded',
    			'content' => $postdata,
    		],
    		'ssl' => [
    			'verify_peer'   => false,
    			'allow_self_signed' => true,
    		]
    	];

        return $opts;
    }

    /**
     * Send SMS messaged with cURL extension
     *
     * @deprecated since 1.0
     * @param string $url sms gateway url
     * @param null $data message
     * @param string $method HTTP method
     * @param null $options cURL options array
     * @param int $retries number of times to retru
     *
     * @return bool|mixed
     */
    private function doRequest($url, $data = null, $method = 'GET', $options = null, $retries = 3, $headers)
	{
    	$result = false;

        if (extension_loaded('curl')) {
            $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_HEADER, true);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	        if (is_array($options) === true)
	        {
	        	curl_setopt_array($curl, $options);
	        }
	
	        for ($i = 1; $i <= $retries; ++$i)
	        {
	        	$result = curl_exec($curl);
	
	            if (($i == $retries) || ($result !== false)) {
	            	break;
	            }
	
	            usleep(pow(2, $i - 2) * 1000000);
	        }
	        curl_close($curl);
	    }
	    return $result;
	}
}
