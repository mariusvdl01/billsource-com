<?php

namespace common\modules\mygate\controllers;

use Yii;
use common\controllers\PaymentModuleController;

class DefaultController extends PaymentModuleController
{
    public function actionIndex()
    {
    	if(Yii::$app->user->isGuest)
    		return $this->goHome();
    	
    	$mygate = Yii::$app->getModule('mygate');
    	$data = $this->processPayment();
    	
        return $this->render('index', [
        	'rpp' 		=> $mygate->getRpp(),
        	'pay_array' => $data['items'],
        	'data'		=> $data['fees'],
        	'index'		=> $data['orderNum'],
        	'returnUrl'	=> $mygate->getReturnUrl(),
        ]);
    }
}
