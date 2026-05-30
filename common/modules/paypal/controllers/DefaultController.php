<?php

namespace common\modules\paypal\controllers;

use Yii;
use common\controllers\PaymentModuleController;

/**
 * Default controller for the `paypal` module
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
			$paypal = Yii::$app->getModule('paypal');
			$paypal->setTransactionDetails($this->request->post());
	        $response = $paypal->doCreateExpressCheckoutPayment();
	        $url = $response->links[1]->href;

	        header('Location: '.$url);
	        die();
    	}
    	$data = $this->processPayment();

        return $this->render('index', [
			'items' => $data['items'],
			'fees'		=> $data['fees'],
			'index'		=> $data['orderNum'],
        ]);
    }

	protected function prepareCheckoutData($post)
	{
		foreach ($post() as $key => $value) {

		}
	}
}
