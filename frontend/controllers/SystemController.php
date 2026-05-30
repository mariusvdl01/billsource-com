<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\helpers\ArrayHelper;
use common\controllers\BusinessController;
use frontend\models\ContactForm;

/**
 * Main entry into the application. Controlls the all the public landing pages for non-aunthenticated users.
 * 
 * 
 * @author Kenneth Onah
 *
 */
class SystemController extends BusinessController
{

	public $defaultAction = 'setup';

    /**
     * Renders public landing pages for un-aunthenticated users.
     *
     * @return string $view home page view script.
     */
    public function actionSetup()
    {
        return $this->render('setup');	
    }
}
