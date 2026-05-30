<?php 

namespace common\modules\mygate\controllers;

use Yii;
use common\models\AuditTrail;
use common\helpers\Billsource;
use common\models\invoice\Invoice;
use common\controllers\PaymentModuleController;

class ValidationController extends PaymentModuleController
{
	public $defaultAction = 'index';
	public $enableCsrfValidation = false;
	
	public function actionIndex()
	{
		if($this->user->isGuest)
			return $this->goHome();
		
		$response = $this->processResult();
		//$this->setUserTheme();
		
		if($response['valid']) {
			$this->session->setFlash('success', 'Sweet!!! Payment was successfully processed');
			$this->session['__invoice_ids'] = null;
		}
		else {
			$this->session->setFlash('error', $response['result']);
		}
		return $this->render('validate', [
			'valid' => $response['valid'],
			'post' => $this->request->post(),
		]);
	}
	
	protected function processResult()
	{
		$request = $this->request;
		$post = $request->post();
		$audit = $this->audit;
		$mygate = $module = Yii::$app->getModule('mygate'); 
		$ip = $this->request->getUserIp();
		
		$audit->log($this->userId, basename($request->url), 'ProcessResult' , 'Processed payment result from Gateway.', 
			$ip);
		$audit->storePaymentResult(basename($request->url));
		return $mygate->processTransaction($post);
	}
}
?>