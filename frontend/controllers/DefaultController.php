<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use common\controllers\BaseController;
use frontend\models\ContactForm;

/**
 * Main entry into the application. Controllers the all the public landing pages for non-aunthenticated users.
 * 
 * 
 * @author Kenneth Onah
 *
 */
class DefaultController extends BaseController
{

	public $defaultAction = 'home';
	/**
	 * Defines how certain actions can be executed irrespective of the default behavior
	 *
	 * @return array $actions defines actions behavior
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow' 	=> true,
						'roles' 	=> ['?', '@'],
					],
				],
			],
		];
	}

    /**
     * Renders public landing pages for un-aunthenticated users.
     *
     * @return string $view home page view script.
     */
    public function actionHome()
    {
		$user = $this->user;

		if (!$user->isGuest) {
            $this->redirectToDashboard($user);
        }


    	$request = $this->request;
    	$tab = $request->get('tab', 'promotions');
    		
    	switch($tab) {
    		case 'home':
    			return $this->render('home', [
    				'tab' => $tab,
    			]);
    		case 'biller':
    			return $this->render('biller', [
    				'tab' => $tab,
    			]);
    		case 'individual':
    			return $this->render('individual', [
    				'tab' => $tab,
    			]);
    		case 'bpo':
    			return $this->render('bpo', [
    				'tab' => $tab,
    			]);
    		case 'vas':
    			return $this->render('vas', [
    				'tab' => $tab,
    			]);
    		case 'counsellor':
    			return $this->render('counsellor', [
    				'tab' => $tab,
    			]);
    		case 'collector':
    			return $this->render('collector', [
    				'tab' => $tab,
    			]);
            case 'marketplace':
                return $this->render('marketplace', [
                    'tab' => $tab,
                ]);
			case 'billi':
				return $this->render('billi', [
					'tab' => $tab,
				]);
    		default:
    			return $this->render('sign-up', [
    				'tab' => $tab,
    			]);
    	}
    }
    
    /**
     * Submits contact information
     *
     * $return View $view renders contact page with contact information.
     */
    public function actionContact()
	{
		$user = $this->user;

		if (!$user->isGuest) {
            $this->redirectToDashboard($user->identity);
        }
		
    	$request = $this->request;
    	$tab = $request->get('tab', 'home');
    	
    	$model = new ContactForm();
    	
    	if ($model->load($request->post()) && $model->validate()) {
    		if ($model->sendEmail()) {
                $this->session->setFlash('contactFormSubmitted');
            }
    		
    		return $this->refresh();
    	} else {
    		return $this->render('contact', [
    				'model' => $model,
    				'tab' => $tab,
    		]);
    	}
    }
}
