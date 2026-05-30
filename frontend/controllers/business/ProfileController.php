<?php

namespace frontend\controllers\business;

use common\models\Bank;
use common\models\BankAccount;
use common\models\BankAccountType;
use common\models\ClientInterface;
use Yii;
use common\models\marketplace\BusinessSubCategory;
use common\models\Title;
use common\models\Province;
use common\controllers\BusinessController;
use common\helpers\ArrayHelper;
use common\helpers\Billsource;
use common\models\bill\UserBillRequest;
use common\models\collector\BinSearch;
use common\models\marketplace\Country;
use common\models\marketplace\BusinessSector;
use common\models\marketplace\BusinessStructure;
use common\models\marketplace\BusinessType;
use common\models\marketplace\BusinessCategory;
use frontend\models\marketplace\business\ProductService;
use frontend\models\marketplace\business\SubProductService;
use common\models\BusinessProfile as Profile;
use frontend\models\ContactForm;
use frontend\models\MarketingMessageForm;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use GuzzleHttp\Client;
use yii\helpers\Url;
use common\models\TransactionLog;

/**
 * The controller class for business authenticated users.
 *
 * @author Kenneth Onah
 *
 */
class ProfileController extends BusinessController
{
	public $defaultAction = 'dashboard';

	public function actions(): array
    {
		return ArrayHelper::merge([
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		], parent::actions());
	}

	/**
     * Renders business user dashboard (home page) after successful login.
     *
     */
    public function actionDashboard(): string
    {
    	$data = $this->client->getProfileData();
        $data['free_sms'] = $data['free_sms'] === '-1' ? 'Unlimited' : $data['free_sms'];
        $data['maximum_limit_invoices'] = $data['maximum_limit_invoices'] === '-1'
            ? 'Unlimited'
            : $data['maximum_limit_invoices'];
        $data['maximum_limit_users'] = $data['maximum_limit_users'] === '-1'
            ? 'Unlimited'
            : $data['maximum_limit_users'];
    	$progress = min(floor(($data['completed'] / ClientInterface::EXPECTED_BUSINESS_FIELD) * 100), 100);
	    $chartsData = $this->client->getDataForCharts();
        $searchModel = new BinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render('dashboard', [
    		'client' 		=> $this->client,
    		'data'  		=> $data,
    		'progress' 		=> $progress,
			'searchModel'	=> $searchModel,
			'dataProvider'	=> $dataProvider,
    		'debtorsData'	=> $chartsData['debtorsData'],
    		'creditorsData'	=> $chartsData['creditorsData'],
			'customersData' => $chartsData['customersData'],
			'customersBarChartData' => $chartsData['customersBarChartData']
    	]);
    }

    public function actionUpgrade()
    {
        $client = $this->client;
        $session = $this->session;

        try {
            $this->checkPermission('updateOwnProfile', $client);
        } catch(ForbiddenHttpException $fhe) {
            $session->setFlash('error', $fhe->getMessage());

            return $this->redirect(['dashboard']);
        }

    	$user = $this->user->identity;
		$client->scenario = 'upgrade';

		if ($this->request->isPost) {
			if ($client->load($this->request->post())) {
				if($client->validate()) {
					if($client->updateProfileProgress()) {
						$session->setFlash('success', 'Profile upgraded successfully');
						return $this->redirect('dashboard');
					}
				}
			}

			$session->setFlash('error', 'An error was encountered');

			return $this->refresh();
		}

		$profiles = $this->getProfiles();
        $client->profile_id = null;
    	return $this->render('upgrade', [
    		'user'		=> $user,
    		'client' 	=> $client,
    		'profiles'	=> $profiles,
    	]);
    }

     public function actionProfileData()
    {
        $id = $this->request->get('id');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return  $this->getProfilesById($id);
    }

    /**
     * Update/Edit business user profile
     *
     * @return string|Response
     * @throws ErrorException
     * @throws Exception|\yii\base\Exception
     */
    public function actionUpdate()
    {
		$client = $this->client;
        $session = $this->session;
		$client->scenario = 'update';

        try {
            $this->checkPermission('updateOwnProfile', $client);
        } catch(ForbiddenHttpException $fhe) {
            $session->setFlash('error', $fhe->getMessage());

            return $this->redirect(['dashboard']);
        }

        $id = $client->user_id;
        $user = $this->user->identity;
    	$request = $this->request;
    	$userBillRequest = UserBillRequest::findOne(['user_id' => $id]);
        $productService = ProductService::findProductService($client->id);
        $bankAccount = BankAccount::findAccount($client->id);
        $productService->category_id = $productService->findBusinessClientProductServiceAll($client->id);
        $subProductService = SubProductService::findSubProductService($productService->category_id);
        $subProductService->sub_category_id = $subProductService->findBusinessCategoryProductServiceAll($productService->category_id);

        if (!$userBillRequest) {
            $userBillRequest = $this->initClientBillRequests($id, $userBillRequest, $user->business_user);
        }

    	if ($request->isPost) {
    		if (
    		    $client->load($request->post(), 'BusinessClient')
                && $userBillRequest->load($request->post(), 'UserBillRequest')
                && $productService->load($request->post(), 'ProductService')
                && $subProductService->load($request->post(), 'SubProductService')
                && $bankAccount->load($request->post(), 'BankAccount')
            ) {
    			if (
    			    $client->validate()
                    && $userBillRequest->validate()
                    && $bankAccount->validate()
                ) {
                    // Upload files before MFA redirect
                    $client->uploadedFile('business_logo');
                    $client->uploadedFile('registration_document', false);

                    $user = $client->getUser()->one();

                    if (!$user->hasTwoFaEnabled()) {
                        if (
                            $client->updateProfilePercentage()
                            && $bankAccount->save()
                            && $userBillRequest->saveBillRequest($id)
                            && $productService->saveProductService($client->id)
                            && $subProductService->saveSubProductService()
                        ) {
                            $session->setFlash('success', 'Profile updated successfully');

                            return $this->redirect(['dashboard']);
                        }
                    }

                    $this->user->createLoginVerificationSession($user);
                    $this->session->set($this->profileSessionKey, [
                        'id' => $client->id,
                        'exp' => time() + (5 * 60),
                        'data' => $request->post()
                    ]);
                    return $this->redirect(['profile-verification']);
    			}
    		}

    		if ($client->hasErrors() || $bankAccount->hasErrors()) {
    			$flash = [];
                $errors = $client->getErrors();

                if (empty($errors)) {
                    $errors = $bankAccount->getErrors();
                }

    			ArrayHelper::recursive($errors, $flash);

    			$session->setFlash('error', $flash);
    		}
    	}

    	$userBillRequest->request_id = $userBillRequest->findAllUserRequestIds($id);
    	$billRequests = Billsource::findUserBillRequestsByType($id);

        if ($client->site_url === null) {
            $siteName = strtolower(md5(uniqid('billsrce').$client->id));
            $client->site_url = $request->getHostInfo() . '/' . $siteName;
        }

    	return $this->render('update', [
            'user_id' 			=> $id,
            'is_business_user' 	=> $user->business_user,
            'client' 			=> $client,
            'userBillRequest' 	=> $userBillRequest,
            'productService'    => $productService,
            'subProductService' => $subProductService,
            'titles' 			=> Title::findAllTitles(),
            'provinces' 		=> Province::findAllProvinces(),
            'billRequests' 		=> ArrayHelper::map($billRequests, 'id', 'description'),
            'countries'         => Country::findAllCountries(),
            'structures'        => BusinessStructure::findAllBusinessStructures(),
            'sectors'           => BusinessSector::findAllBusinessSectors(),
            'types'             => BusinessType::findAllBusinessTypes(),
            'categories'        => BusinessCategory::findAllBusinessCategories(),
            'subcategories'     => BusinessSubCategory::findAllBusinessSubCategories(),
            'banks'             => Bank::findAllBanks(),
            'accountTypes'      => BankAccountType::findAllAccountTypes(),
            'bankAccount'       => $bankAccount
    	]);
    }

    /**
     * Submits contact information
     *
     * $return View $view renders contact page with contact information.
     */
    public function actionContact() {
    	$request = $this->request;
    	$model = new ContactForm();
    	$user = $this->user->identity;

    	if ($model->load($request->post()) && $model->sendEmail()) {
    		$this->session->setFlash('contactFormSubmitted');

    		return $this->refresh();
    	}

    	return $this->render('/default/contact', [
    		'model' => $model,
            'email' => $user->email,
            'from' => $user->businessClient->contact_person,
    	]);
    }

    public function actionMessage()
    {
    	$model = new MarketingMessageForm;
    	$user_id = $this->userId;

    	if($model->load($this->request->post()) && $model->validate()) {
    		$model->updateMessage($user_id);
    		$this->session->setFlash('sucess', 'Marketing Message updated successfully');

    		return $this->redirect('dashboard');
    	}

    	if($model->hasMarketingMessage($user_id))
    		$model->marketing_message = $model->getClientMarketingMessage($user_id);

    	return $this->render('message', [
    		'model'	=> $model
    	]);
    }

    // Subscribe plan with paystack transaction
      public function actionSubscribe()
    {
        $client = $this->client;

        // check dwongrad
        $currentPlan = $client->profile_id;

        // assume you have a plans table with price
        $currentPlan = Profile::findProfileById($currentPlan);
        $profile = Profile::findProfileById(Yii::$app->request->post('profileid'));

        $newPrice = $profile['fee'];
        $currentPrice = $currentPlan['fee'];

        if ($newPrice > $currentPrice) {
            $actionType = 'upgrade';
        } elseif ($newPrice < $currentPrice) {
            $actionType = 'downgrade';
        } else {
            $actionType = 'same'; // no change
        }

        // check date for downgrade
        if ($actionType === 'downgrade') {
            $subscriptionDate = new \DateTime($client->subscribed_date);
            $now = new \DateTime();
            $diff = $subscriptionDate->diff($now);

            // If subscription is less than 6 months old
            if ($diff->m + ($diff->y * 12) < 6) {
                Yii::$app->session->setFlash('error', 'You can downgrade only after 6 months of subscription.');
                return $this->redirect(['business/profile/upgrade']); // or wherever
            }
        }


        $session = $this->session;

        try {
            $this->checkPermission('updateOwnProfile', $client);
        } catch(ForbiddenHttpException $fhe) {
            $session->setFlash('error', $fhe->getMessage());

            return $this->redirect(['dashboard']);
        }
        
       
        $email = Yii::$app->request->post('email'); // from form
        $plan_code = $profile['plan']; // You must create this in Paystack dashboard or via API

        $clientCurd = new Client();

        try {
            $response = $clientCurd->post('https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer '.Yii::$app->params['payStackKey'],
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'email' => $email,
                    'amount' => $profile['fee'],
                    'plan' => $plan_code,
                    'callback_url' => Url::to(['/business/profile/verify'], true),
                    'metadata' => [
                        'profile_id' => Yii::$app->request->post('profileid'), // 👈 attach here
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            return $this->redirect($result['data']['authorization_url']);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Payment initialization failed: ' . $e->getMessage());
            return $this->goBack();
        }
    }

    // verify transaction and plans
    public function actionVerify()
    {
        $reference = Yii::$app->request->get('reference');

        $clientCurd = new Client();
        $response = $clientCurd->get("https://api.paystack.co/transaction/verify/{$reference}", [
            'headers' => [
                'Authorization' => 'Bearer '.Yii::$app->params['payStackKey'],
                'Content-Type' => 'application/json',
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        // ✅ Save log no matter success/failure
        $log = new TransactionLog;
        $log->client_id = Yii::$app->user->id;
        $log->profile_id = $result['data']['metadata']['profile_id'] ?? null;
        $log->reference = $reference;
        $log->amount = $result['data']['amount'] / 100; // Paystack sends kobo
        $log->status = $result['data']['status'];
        $log->response = json_encode($result['data']); // save full response
        $log->save(false);

        if ($result['data']['status'] === 'success') {
           $profileId = $result['data']['metadata']['profile_id']; // 👈 get it back
           $this->clientUpgrade($this->client, $profileId);
            // Save subscription info, mark as paid, etc.
            Yii::$app->session->setFlash('success', 'Profile upgraded successfully!');
        } else {
            Yii::$app->session->setFlash('error', 'Payment verification failed!');
        }

        return $this->redirect('dashboard');
    }

     public function clientUpgrade($client, $profile)
    {
		$client->scenario = 'upgrade';
        $client->profile_id = $profile;
        $client->subscribed_date = date('Y-m-d');
        $client->is_subscribed = true;
        if($client->save()) {
            Yii::$app->session->setFlash('success', 'Profile upgraded successfully');
            return $this->redirect('dashboard');
        }

    }
}
