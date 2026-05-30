<?php 

namespace common\modules\payu\controllers;

use Yii;
use common\controllers\PaymentModuleController;

class ValidationController extends PaymentModuleController
{
	protected $payuModule = null;

	public $defaultAction = 'index';
	public $enableCsrfValidation = false;

	public function init()
	{
		parent::init();

		if(is_null($this->payuModule)){
			$this->payuModule = Yii::$app->getModule('payumea');
		}
	}
	public function actionIndex()
	{
		$user = $this->user;
		if($user->isGuest)
			return $this->goHome();

		$approved = false;
        $response = null;
        $url = $user->identity->business_user ? '/business/creditor/unpaid' : '/individual/bill/unpaid';
		$params = $this->request->getQueryParams();
		$payuReference = isset($params['PayUReference']) ? $params['PayUReference'] : $params['payUReference'];

        if(!empty($payuReference)) {
            try {
                $response = $this->payuModule->doGetTransaction($payuReference);
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect($url);
            }
        }

        if($response && $response->successful && 'SUCCESSFUL' == $response->transactionState) {
            $approved = true;
            $this->session->setFlash('success', 'Sweet!!! Payment was successfully approved');
        } elseif ($response->resultCode && 'P015' == $response->resultCode)  {
            $this->session->setFlash('error', 'User cancelled the payment transaction');
        } else {
            $this->session->setFlash('error', 'Oops!!! Payment failed');
        }

        $txnResult = $this->processResponse($response, $user);
        $this->session->remove('invoice.ids');

		return $this->render('index', [
			'success' => $response->successful,
			'approved' => $approved,
			'txnResult' => $txnResult
		]);
	}

	protected function processResponse($response, $user)
	{
		$audit = $this->audit;
		$ip = $this->request->getUserIP();

		$audit->log($this->userId, $this->action->uniqueId, 'ProcessResult' ,
			'Processed payment response from Gateway.', $ip);
		$audit->storePaymentResult($this->action->uniqueId, $response);
		return $this->payuModule->processTransaction($response, $user);
	}
}
?>