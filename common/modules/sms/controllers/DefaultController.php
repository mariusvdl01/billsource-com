<?php

namespace common\modules\sms\controllers;

use common\controllers\BaseController;
use common\modules\sms\SmsGateway;

/**
 * Default SmsGateway controller class 
 * 
 * @author Kenneth Onah
 *
 */
class DefaultController extends BaseController
{
	/**
	 * Default module controller action 
	 */
    public function actionIndex()
    {
        return $this->goHome();
    }
}
