<?php

namespace frontend\controllers\individual;

use common\helpers\ArrayHelper;
use common\helpers\Billsource;
use common\models\AuditTrail;
use common\models\bill\UserBillRequest;
use common\models\ContactForm;
use common\models\individual\AssistanceForm;
use common\models\individual\IndividualClient;
use common\models\individual\IndividualFinancial;
use common\models\invoice\InvoiceSearch;
use common\models\Province;
use common\models\Title;
use common\Registry;
use yii\web\ForbiddenHttpException;

/**
 * The controller class for individual authenticated users.
 * 
 * @author Kenneth Onah
 *
 */
class ProfileController extends \common\controllers\IndividualController
{
    /**
     * @var string the ID of the action that is used when the action ID is not specified
     * in the request. Defaults to 'index'.
     */
    public $defaultAction = 'dashboard';

    public function actions()
    {
        return ArrayHelper::merge([
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ], parent::actions());
    }

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Renders individual user dashboard (home page) after successful login.
     *
     * @return $view home page view script.
     */
    public function actionDashboard()
    {
        $client = $this->client;
        $user_id = $client->user_id;
        $searchModel = new InvoiceSearch();
        $data = $client->getProfileData();
        $payments = $client->getPaidInvoice();
        $oldestInvoice = $client->findOldestInvoice();
        $outstanding = $client->getTotalOutstandingBills();
        $progress = ceil(($data->completed / IndividualClient::EXPECTED_FIELD) * 100);
        $dataProvider = $searchModel->searchForIndividual($user_id, $this->request->queryParams);

        if(!$client->hasCompleteProfile($data))
            $this->session->setFlash('warning',
                'Address details, Mobile and ID number is required to request a loan or assistance and
                to view invoices. Please update your profile');

        return $this->render('dashboard', [
            'data' => $data,
            'payments' => $payments,
            'outstanding' => $outstanding,
            'oldestInvoice' => $oldestInvoice,
            'progress' => $progress,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ratio' => $this->calculateRatios($data->individualFinancials),
        ]);

    }
    
    public function actionUpdate()
    {
        $client = $this->client;
        $session = $this->session;
        try {
            $this->checkPermission('updateOwnProfile', $client);
        } catch(ForbiddenHttpException $fhe) {
            $session->setFlash('error', $fhe->getMessage());
            return $this->redirect(['dashboard']);
        }

        $client->scenario = 'update';
        $id = $client->user_id;
        $user = $this->user->identity;
        $userBillRequest = UserBillRequest::findOne(['user_id' => $id]);
        $request = $this->request;
        Registry::register('user', $user);

        if(!$userBillRequest)
            $userBillRequest = $this->loadClientBillRequests($id, $userBillRequest, $user->business_user);

        if($request->isPost) {
            if ($client->load($request->post(), 'IndividualClient')
                && $userBillRequest->load($request->post(), 'UserBillRequest')) {

                if($client->validate() && $userBillRequest->validate()) {
                    $client->uploadImageFile();
                    if($client->updateProfile() && $userBillRequest->saveBillRequest($id)) {
                        $session->setFlash('success', 'Profile updated successfully');
                        return $this->redirect('dashboard');
                    }
                }
            }
            if($client->hasErrors()) {
                $flash = [];
                ArrayHelper::recursive($client->getErrors(), $flash);
                $session->setFlash('error', $flash);
            }
        }

        $userBillRequest->request_id = $userBillRequest->findAllUserRequestIds($id);
        $billRequests = Billsource::findUserBillRequestsByType($id);

        return $this->render('update', [
            'user_id' => $id,
            'is_business_user' => $user->business_user,
            'client' => $client,
            'userBillRequest' => $userBillRequest,
            'titles' => Title::findAllTitles(),
            'provinces' => Province::findAllProvinces(),
            'billRequests' => ArrayHelper::map($billRequests, 'id', 'description')
        ]);
    }
    
    public function actionAssistance($tab) 
    {
        $user_id = $this->userId;
        $request = $this->request;
        $session = $this->session;
        $model = new AssistanceForm();
        $audit = new AuditTrail();
        $ipAddr = $request->getUserIp();

        if ($model->load($request->post())) {
            if($request->post('counselling')) {
                if($model->submitCounsellingRequest($user_id)) {
                    $session->setFlash('success', 'We have received your request for assistance.');
                    $audit->log($user_id,  get_class($this), __METHOD__, 'User successfully requested assistance.',
                    $ipAddr);
                } else {
                    $session->setFlash('info', 'You have previously submitted a request for assistance.');
                }

                return $this->redirect(['dashboard']);
            }

            if($request->post('loan') && $model->submitLoanRequest($user_id)) {
                $session->setFlash('success', 'We have received your request for a loan.');
                $audit->log($user_id,  get_class($this), __METHOD__, 'User successfully requested a loan.',
                    $ipAddr);
                return $this->redirect(['dashboard']);
            }
            $audit->log($user_id,  get_class($this), __METHOD__, 'User request failed', $ipAddr);
            $session->setFlash('error', 'Cannot send request. An error was encountered.');
        }

        $complete = false;
        if($this->client->hasCompleteProfile($this->client->getProfileData($user_id)))
            $complete = true;

        return $this->render('assistance', [
            'model' => $model,
            'tab' => $tab,
            'complete' => $complete,
        ]);
    }
    
    /**
     * Submits contact information
     *
     * $return View $view renders contact page with contact information.
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $user = $this->user->identity;

        if ($model->load($this->request->post()) && $model->sendEmail()) {
            $this->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('/default/contact', [
            'model' => $model,
            'email' => $user->email,
            'from' => $user->individualClient->first_name . ' ' . $user->individualClient->last_name,
        ]);
    }
    
    /**
     * Submits contact information
     *
     * $return View $view renders contact page with contact information.
     */
    public function actionFinancial()
    {
        $id = $this->userId;
        $request = $this->request;
        $session = $this->session;
        $client = $this->client;
        $data = $client->getProfileData($id);
        $financial = IndividualFinancial::findOne(['individual_id' => $client->id]);

        if($request->isPost) {
            $financial->loadDefaultValues();
            if($financial->load($request->post()) && $financial->save()) {
                $session->setFlash('success', 'Financial details saved successful');
                return $this->redirect(['dashboard']);
            }
            $session->setFlash('error', 'Server error encountered while saving financial details');
        }
        if(!$financial) {
            $financial = new IndividualFinancial;
            $financial->individual_id = $client->id;
            $financial->save();
        }
        if(!$client->hasCompleteProfile($data)) {
            $session->setFlash('info', 'Address details, Mobile and ID number is required to update financial. 
            Please update your profile');
            return $this->redirect(['/individual/profile/update']);
        }

        return $this->render('financial', [
            'financial' => $financial,
            'data' => $data,
        ]);
    }

    protected function calculateRatios($indFinancials) 
    {
        $financials = $indFinancials;
        $assets = 0.0;
        $liabilities = 0.0;
        $surplus = 0.0;

        foreach ($financials as $value) {
            $assets += $value->total_assets;
            $liabilities += $value->total_liabilities;
            $surplus += $value->surplus;
        }

        return $ratio  = [
            'assets' => $assets, 
            'liabilities' => $liabilities, 
            'surplus' => $surplus
        ];
    }
}