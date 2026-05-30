<?php

namespace common\modules\payu\controllers;

use Yii;
use common\controllers\PaymentModuleController;

/**
 * Default controller for the `payu-mea` module
 */
class DefaultController extends PaymentModuleController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if($this->user->isGuest)
    		return $this->goHome();
		
  		if($this->request->isPost) {
			$payu = Yii::$app->getModule('payumea');
			$payu->setTransactionData($this->request->post());
	        $response = $payu->doSetTransaction();

	        if(!$response->successful) {
	        	$this->session->setFlash('error', 'Error code: ' . $response->resultCode . ', Error message: ' . $response->resultMessage);
	        } else {
	        	$url = $payu->getRedirectUrl() . '?PayUReference=' . $response->payUReference;
	        	header('Location: '.$url);
	        	die();
	        }
    	}
    	$data = $this->processPayment();

        return $this->render('index', [
			'items' => $data['items'],
			'fees'		=> $data['fees'],
			'index'		=> $data['orderNum'],
        ]);
    }
}
