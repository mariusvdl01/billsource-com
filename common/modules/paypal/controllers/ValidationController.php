<?php 

namespace common\modules\paypal\controllers;

use Yii;
use common\models\AuditTrail;
use common\helpers\Billsource;
use common\models\invoice\Invoice;
use common\controllers\PaymentModuleController;

class ValidationController extends PaymentModuleController
{
	protected $paypalModule = null;

	public $defaultAction = 'index';
	public $enableCsrfValidation = false;

	public function init()
	{
		parent::init();

		if(is_null($this->paypalModule)){
			$this->paypalModule = Yii::$app->getModule('paypal');
		}
	}
	public function actionIndex()
	{
		$user = $this->user;
		if($user->isGuest)
			return $this->goHome();

		$authorize = false;
		$approved = false;
        $url = $user->identity->business_user ? '/business/creditor/unpaid' : '/individual/bill/unpaid';
		$queryParams = $this->request->getQueryParams();
		$success = isset($queryParams['success']) ? $queryParams['success'] : 'false';
		if($success == 'true') {
			$paymentId = $queryParams['paymentId'];
			$payerId = $queryParams['PayerID'];

			try {
				$response = $this->paypalModule->doExecuteExpressCheckoutPayment($paymentId, $payerId);
			} catch (\PayPal\Exception\PayPalConnectionException $e) {
				$exception = json_decode($e->getData(), true);
				$this->session->setFlash('error', $exception['message']);
				return $this->redirect($url);
	        }

			if('approved' == $response->getState()) {
				$approved = true;
				$this->session->setFlash('success', 'Sweet!!! Payment was successfully approved');
			} else {
				$this->session->setFlash('error', 'Oops!!! Payment failed to be approved');
			}

			$txnResult = $this->processResponse($response, $user);
			$this->session->remove('invoice.ids');
		}

		if($success == 'false') {
			$authorize = true;
			$this->session->setFlash('error', 'User cancelled the approval');
		}

		return $this->render('index', [
			'authorize' => $authorize,
			'success' => $success,
			'approved' => $approved,
			'txnResult' => $txnResult
		]);
	}

	protected function processResponse($response, $user)
	{
		$request = $this->request;
		$audit = $this->audit;
		$ip = $this->request->getUserIP();

		$audit->log($this->userId, $this->action->uniqueId, 'ProcessResult' ,
			'Processed payment response from Gateway.', $ip);
		$audit->storePaymentResult($this->action->uniqueId, $response);
		return $this->paypalModule->processTransaction($response, $user);
	}
}
?>