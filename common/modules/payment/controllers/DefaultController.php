<?php

namespace common\modules\payment\controllers;

use Yii;
use yii\web\Controller;
use common\controllers\PaymentModuleController;

/**
 * Default controller for the `paymentHandler` module
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
      
    	$request = $this->request;

    	if($request->isPost) {
          if (!empty($request->post('payment'))) {
            return $this->redirect(['/' . $request->post('payment')]);
          } else {
            $this->session->setFlash('error', 'Please choose a payment method');
          }
        }

        return $this->render('index', [
        	'paymentPlugins' => Yii::$app->getModule('payment')->getPaymentPlugins()
        ]);
    }
}
