<?php

namespace frontend\controllers;

use common\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\controllers\BaseController;
use frontend\models\SignupForm;
use frontend\models\ActivateEmailForm;
use frontend\models\UserLoginForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use yii\web\View;

/**
 * This Controller class takes  care of user account functionality such as
 * sign up, login, logout, activation, and password reset.
 * 
 * @author Kenneth Onah
 *
 */
class AccountController extends BaseController
{
	/**
	 * Defines behaviors to attach to actions in this class.
	 * 
	 * @return array $behaviors an array of behaviors
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only' => ['logout'],
				'rules' => [
					[
						'allow' 	=> true,
						'roles' 	=> ['@'],
					]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}
	
	/**
	 * User registration.
	 * 
	 * @return string|Response $response renders the signup view script or redirects to login
	 */
    public function actionSignup()
    {
    	$request = $this->request;
    	$model = new SignupForm();
        
    	if ($model->load($request->post()) && $model->validate()) {
    		
    		if (($user = $model->signup()) !== false) {
                $model->sendEmail();
    			$this->session->setFlash('success', 'Your account was created successfully. Please check your mailbox
    			                         and click verify link.');
				return $this->redirect(['login']);
    		}

            if ($model->hasErrors()) {
                $flash = [];
                $errors = $model->getErrors();

                ArrayHelper::recursive($errors, $flash);
            }
    		$this->session->setFlash('error', $flash ?? 'Server error encountered while creating your account');
    	}

        $tab = $request->get('tab');
    	$model->category = $tab;

    	return $this->render('signup', [
    			'model' => $model,
    			'tab' => $tab,
    	]);
    }
    
    /**
     * Handles password reset 
     * 
     * @return string $view | Response $response renders the password reset view  script or redirects
	 * to home
     */
    public function actionRequestPasswordReset()
    {
    	$model = new PasswordResetRequestForm();
    	if ($model->load($this->request->post()) && $model->validate()) {
    		if ($model->sendEmail()) {
    			$this->session->setFlash('success', 'Check your email for further instructions.');
    			return $this->goHome();
    		} else {
    			$this->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
    		}
    	}
    
    	return $this->render(
            'requestPasswordResetToken',
            [
    			'model' => $model
            ]
        );
    }
    
    /**
     * Password reset. Update users' password with new given password
     * 
     * @param string $token password reset token
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
    	try {
    		$model = new ResetPasswordForm($token);
    	} catch (InvalidParamException $e) {
    		throw new BadRequestHttpException($e->getMessage());
    	}
    
    	if ($model->load($this->request->post()) && $model->validate() && $model->resetPassword()) {
    		$this->session->setFlash('success', 'New password was saved.');
    
    		return $this->goHome();
    	}
    
    	return $this->render('resetPassword', [
    			'model' => $model,
    	]);
    }
    
    /**
     * Activate user email after registration
     *
     * @param string $token password reset token
     * @return View $view the activation view script
     * @throws BadRequestHttpException
     */
    public function actionActivate($email, $token)
	{
    	try {
    		$model = new ActivateEmailForm(urldecode($email), urldecode($token));
    	} catch (InvalidParamException $e) {
    		throw new BadRequestHttpException($e->getMessage());
    	}
    
    	if ($model->activateEmail()) {
    		$this->session->setFlash('success', 'Your have successfully confirmed your email');
    		$model->sendEmail();

    		return $this->goHome();
    	} else {
    		$this->session->setFlash('error', 'Invalid email or expired token.');
			$user = $this->user;

			if (!$user->isGuest) {
                $this->redirectToDashboard($user->identity);
            }

    		return $this->goHome();
    	}
    }
    
    /**
     * Login user and redirect user to individual or business user home page.
     * 
     * After login is successful and before redirection, an audit of the event is 
     * generated and stored for reference
     *
     * @return string|Response $view | Response $response renders login view script or redirects to user home
     * page.
     */
    public function actionLogin()
    {
		$authUser = $this->user;

		if (!$authUser->isGuest) {
            $this->redirectToDashboard($authUser->identity);
        }

    	$model = new UserLoginForm;
    	$audit = $this->audit;
    	$ip = $this->request->getUserIP();
    	
    	if ($model->load($this->request->post()) && $model->validate()) {
            $user = $model->getUser();

            if (!$user->hasTwoFaEnabled()) {
                if ($model->login()) {
                    if ($authUser->identity->business_user) {
                        $model->sendEmail();
                    }

                    $model->updateLastLogin();
                    $this->redirectToDashboard($authUser);
                } else {
                    $audit->log(
                        '0',
                        get_class($this),
                        'Login',
                        "User ($model->email) login unsuccessful",
                        $ip
                    );
                }
            }

            $authUser->createLoginVerificationSession($user);

            return $this->redirect(['login-verification']);
    	}

        $model->password = '';

    	return $this->render('login', ['model' => $model]);
    }
    
    /**
     * Logout user
     *
     * @return string $homeUrl application home page
     */
    public function actionLogout()
    {
    	$this->user->logout();
    	$this->session->setFlash('success', 'Your have successfully logged out');

    	return $this->goHome();
    }
}
