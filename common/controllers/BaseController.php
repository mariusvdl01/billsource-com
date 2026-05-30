<?php

namespace common\controllers;

use common\helpers\Billsource;
use common\models\AuditTrail;
use common\models\AssistanceForm;
use common\models\BankAccount;
use common\models\bill\UserBillRequest;
use common\models\business\BusinessClient;
use common\models\ContactForm;
use common\models\LoginForm;
use common\models\individual\IndividualClient;
use common\models\invoice\Invoice;
use common\models\invoice\Quote;
use common\models\marketplace\Country;
use common\models\Status;
use common\models\User;
use common\models\Vault;
use frontend\assets\BillsourceAsset;
use frontend\models\marketplace\business\ProductService;
use frontend\models\marketplace\business\SubProductService;
use frontend\models\ProfileTwoFaForm;
use frontend\models\SignupForm;
use frontend\models\UserLoginForm;
use frontend\models\WithdrawTokenForm;
use promocat\twofa\models\TwoFaForm;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\AssetBundle;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;
use yii\web\ServerErrorHttpException;
use yii\web\View;

/**
 * Base controller class provides utility methods to all child class that extends from it.
 * The methods set global paramaters, generate pdfs as well as handle events.
 *
 * @author Kenneth Onah
 *
 */
class BaseController extends Controller
{
    /**
     * An instance of assetBundle manager
     * @var AssetBundle $assetBundle
     */
    protected $assetBundle = null;

    /**
     * An instance of Individual or Business client currently authenticated
     * @var IndividualClient | BusinessClient $client model
     */
    protected $client = null;

    /**
     * An instance of LoginForm for top header login
     * @var LoginForm form model
     */
    protected $loginForm;

    /**
     * An instance of SignupForm for sidebar
     * @var SignupForm sign up form model
     */
    protected $signupForm;

    /**
     * The id of the current user
     * @var  integer this property is read-onl
     */
    protected $userId;

    /**
     * The current user instance
     * @var User this property is read-only
     */
    protected $user;

    /**
     * An instance of the session application component
     * @var Session this property is read-only
     */
    protected $session;

    /**
     * An instance of the request application component
     * @var  Request this property is read-only
     */
    protected $request;

    /**
     * An instance of the response application component
     * @var Response This property is read-only
     */
    protected $response;

    /**
     * An instance of the audit logger
     * @var AuditTrail this property is read-only
     */
    protected $audit;

    /**
     * Counter that keeps track of unread bills
     * @var array this property is read-only
     */
    protected $unreadBillsCounter;

    /**
     * @var string
     */
    protected $profileSessionKey = 'profile';

    /**
     * Defines behaviors to attach to actions in this class.
     * @return array $behaviors an array of behaviors
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Defines how certain actions can be executed irrespective of the default behavior
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Handles beforeAction event trigger before controller actions are executed
     * @param Action $action current action being executed
     * @return boolean
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!Yii::$app->user->isGuest) {
                $user = Yii::$app->user->identity;
                if ($user->isTrialExpired() && !$user->client->is_subscribed) {
                    // Allow only subscription-related actions
                    if (!in_array($this->id, ['business/profile', 'account'])) {
                        Yii::$app->response->redirect(['/business/profile/upgrade'])->send();
                        return false;
                    }
                }
            }
            $this->initProperties();
            return true;
        } else {
            return false;
        }
        
    }

    /**
     * Set global application variables;
     *
     */
    protected function initProperties()
    {
        try {
            $this->initLoginForm();
            $this->initSignupForm();
            $this->initUser();
            $this->initUserId();
            $this->initSession();
            $this->initRequest();
            $this->initResponse();
            $this->initUserName();
            $this->initAuditTrailInstance();
            $this->loadUserRoleById();
            $this->loadAssetBundle();
            $this->initUnreadBillsCounter();
        } catch (ServerErrorHttpException $se) {
            throw new ServerErrorHttpException('Server Error encountered', 500);
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Server Error encountered. Reason: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Creates a LoginForm instance and make available in any part of the application
     *
     */
    protected function initLoginForm()
    {
        if (!$this->loginForm) {
            $this->loginForm = new UserLoginForm();
            Yii::$app->params['model'] = $this->loginForm;
        } else {
            Yii::$app->params['model'] = $this->loginForm;
        }
    }

    /**
     * Creates a LoginForm instance and make available in any part of the application
     *
     */
    protected function initSignupForm()
    {
        if (!$this->signupForm) {
            $this->signupForm = new SignupForm();
            Yii::$app->params['signupForm'] = $this->signupForm;
        } else {
            Yii::$app->params['signupForm'] = $this->signupForm;
        }
    }

    /**
     * Sets the 'user' property to the current authenticated user.
     */
    protected function initUser()
    {
        if (!$this->user) {
            $this->user = Yii::$app->user;
        }
    }

    /**
     * Sets the 'userId' property to the id of the current authenticated user.
     */
    protected function initUserId()
    {
        if (!$this->userId) {
            $this->userId = Yii::$app->session['__id'];
        }
    }

    /**
     * Sets the 'session' property to the current active session.
     */
    protected function initSession()
    {
        if (!$this->session) {
            $this->session = Yii::$app->getSession();
        }
    }

    protected function initRequest()
    {
        if (!$this->request) {
            $this->request = Yii::$app->request;
        }
    }

    protected function initResponse()
    {
        if (!$this->response) {
            $this->response = Yii::$app->response;
        }
    }

    protected function initUserName()
    {
        $client = null;

        if ($this->userId) {
            $client = BusinessClient::findOne(['user_id' => $this->userId]);
            if (is_null($client)) {
                $client = IndividualClient::findOne(['user_id' => $this->userId]);
            }
        }

        Yii::$app->session['__userName'] = $client->contact_person ?? ($client->first_name ?? '');
    }

    /**
     * Create audit trail object
     */
    protected function initAuditTrailInstance()
    {
        if (!$this->audit) {
            $this->audit = new AuditTrail();
        }
    }

    /**
     * Load user role by user id
     */
    protected function loadUserRoleById()
    {
        $id = $this->userId;
        $auth = Yii::$app->authManager;

        if ($auth) {
            Yii::$app->params['__role'] = key($auth->getRolesByUser($id));
        }
    }

    /**
     * @throws InvalidConfigException
     */
    public function loadAssetBundle()
    {
        if (!$this->assetBundle) {
            $view = $this->getView();
            $this->setJavaScriptVariable($view);
            $this->assetBundle = $view->registerAssetBundle(BillsourceAsset::class);
            Yii::$app->params['assetBundle'] = $this->assetBundle;
        }
    }

    /**
     * Initialize bill counter
     */
    public function initUnreadBillsCounter()
    {
        $identity = $this->user->identity;
        Yii::$app->params['unreadBillsCounter']['QTN'] = 0;
        Yii::$app->params['unreadBillsCounter']['INV'] = 0;
        Yii::$app->params['unreadBillsCounter']['CR'] = 0;
        Yii::$app->params['unreadBillsCounter']['TCK'] = 0;

        if ($identity && !$this->unreadBillsCounter) {
            if ($identity->business_user) {
                $this->unreadBillsCounter = Invoice::getBusinessUnreadBillsCounter($identity);
            } else {
                $this->unreadBillsCounter = Invoice::getIndividualUnreadBillsCounter($identity);
            }

            foreach ($this->unreadBillsCounter as $key => $items) {
                foreach ($items->readAll() as $item) {
                    Yii::$app->params['unreadBillsCounter'][$key] = $item['counter'];
                }
            }
        }
    }

    /**
     * @param View $view
     */
    protected function setJavaScriptVariable(View $view)
    {
        $view->registerJsVar('tax_rate', Yii::$app->params['tax_rate']);
    }

    /**
     * Submits contact information
     * @deprecated since v1.0.1
     * @return string $view renders contact page with contact information.
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $user = $this->user->identity;

        if ($user && !$user->business_user) {
            $user = IndividualClient::findIdentity($user->id);
        } elseif ($user && $user->business_user) {
            $user = BusinessClient::findIdentity($user->id);
        }

        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
            'user'  => $user,
        ]);
    }

    /**
     * @return bool[]
     */
    public function actionAcceptQuote()
    {
        $this->response->format = Response::FORMAT_JSON;

        if ($this->request->isAjax) {
            $data = $this->request->post();
            $id = $data['id'];
            $quote = Quote::findOne(['id' => $id]);

            if ($quote) {
                if ($quote->acceptQuote()) {
                    $this->session->setFlash('success', 'Quote accepted successfully and biller notified');

                    return ['success' => true];
                }
            }
        }

        $this->session->setFlash('error', 'Server encountered error while processing request.');

        return ['success' => false];
    }

    /**
     * @param $id
     * @return void|Response
     */
    public function actionView($id)
    {
        $options = [
            'title' => 'Quotation',
            'headerDesc' => 'Your Quotation Description!'
        ];
        $template = '/pdf/template';
        $billsource = new Billsource();
        $user = $this->user->identity;

        if (!$user->getClient()->hasCompleteProfile()) {
            $this->session->setFlash(
                'info',
                'Address details, registration, mobile and ID number is required to view invoices'
            );

            if ($user->business_user) {
                return $this->redirect('/business/profile/update');
            } else {
                return $this->redirect('/individual/profile/update');
            }
        }

        $data = $billsource->loadInvoice($id);

        if (0 == strcasecmp('0', $data['invoice']['business_id'])) {
            $this->renderVaultedPdfFile($data['invoice']);

            return;
        }

        switch ($data['invoice']['type']) {
            case 'INV':
                $options['title'] = 'Tax Invoice';
                $options['headerDesc'] = 'Your Billing Description!';

                if ($data['invoice']['paid'] == 1) {
                    $template = '/pdf/receipt';
                    $options['title'] = 'Receipt';
                    $options['headerDesc'] = 'Payment made with Billsource';
                    $data = $billsource->loadPaidInvoice($id);
                }

                if ($data['status']['code'] === Status::STATUS_REFUND) {
                    $options['title'] = 'Credit Note';
                }

                break;

            case 'CNV':
                $options['title'] = 'Cash Invoice';
                $options['headerDesc'] = 'Your Cash Invoice Description!';
                break;

            case 'PYP':
                $options['title'] = 'Payslip';
                $options['headerDesc'] = 'Your Payslip Description!';
                break;

            case 'TCK':
                $options['title'] = 'Ticket';
                break;
        }

        $pdf = Yii::$app->pdf;
        $header = Html::encode('Your professional Biller Service Provider') . '|| ';
        $pdf->options = $options;
        $pdf->methods = ['SetHeader' => $header, 'SetFooter' => '{PAGENO}'];
        $htmlContent = $this->renderPartial($template, [
            'invoice' => $data['invoice'],
            'lines'   => $data['lines'],
            'biller'  => $data['biller'],
            'bankAccount' => $data['bankAccount'] ?? null,
            'options' => $options
        ]);

        $pdf->content = $htmlContent;
        $pdf->filename = $data['invoice']['reference_number'] . '.pdf';

        return $pdf->render();
    }

    /**
     * @param $invoice
     */
    protected function renderVaultedPdfFile($invoice)
    {
        $path = Yii::getAlias(Vault::VAULT_DIR);
        $pdfFile = $path . DIRECTORY_SEPARATOR . $invoice['pdf'];

        if (file_exists($pdfFile)) {
            $this->response->sendFile($pdfFile, $invoice['reference_number'] . '.pdf');
            $this->response->send();
        }
    }

    /**
     * @return BusinessClient|IndividualClient|null
     */
    public function getClient()
    {
        return $this->client ?? null;
    }

    /**
     * Redirects user to home page if authenticated and logged in
     * @return Response
     */
    protected function preDispatch()
    {
        $user = Yii::$app->user;

        if ($user->isGuest) {
            return $this->redirect(['/account/login']);
        }

        return $this->redirectToDashboard($user);
    }

    /**
     * @deprecated since v1.0.1
     */
    protected function setUserTheme()
    {
        $user = $this->user->identity;

        if ($user->business_user) {
            $this->layout = '//business/main';
        } else {
            $this->layout = '//individual/main';
        }
    }

    /**
     * @param $permission
     * @param $client
     * @throws ForbiddenHttpException
     */
    protected function checkPermission($permission, $client)
    {
        if (!$this->user->can($permission, ['client' => $client])) {
            throw new ForbiddenHttpException('You are not authorized to perform this action', 403);
        }
    }

    /**
     * @param $id
     * @param $billRequest
     * @param $type
     * @return UserBillRequest
     */
    protected function initClientBillRequests($id, $billRequest, $type)
    {
        if (!isset($billRequest)) {
            $billRequest = new UserBillRequest();
            $billRequest->user_id = $id;
            $billRequest->is_business_user = $type;
        }

        return $billRequest;
    }

    /**
     * @param $user
     * @return Response
     */
    protected function redirectToDashboard($user)
    {
        $audit = $this->audit;
        $ip = $this->request->getUserIP();
        $identity = $user->identity ?? $user;

        if ($identity->business_user) {
            $url = '/business/ecosystem';
            $memo = 'Business user login successful';
        } else {
            $url = '/individual/profile';
            $memo = 'Individual user login successful';
        }

        $audit->log($identity->id, get_class($this), 'Login', $memo, $ip);

        return $this->redirect([$url]);
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
            if ($request->post('counselling')) {
                if ($model->submitCounsellingRequest($user_id)) {
                    $session->setFlash('success', 'We have received your request for assistance.');
                    $audit->log(
                        $user_id,
                        get_class($this),
                        __METHOD__,
                        'User successfully requested assistance.',
                        $ipAddr
                    );
                } else {
                    $session->setFlash('info', 'You have previously submitted a request for assistance.');
                }

                return $this->redirect(['dashboard']);
            }

            if ($request->post('loan') && $model->submitLoanRequest($user_id)) {
                $session->setFlash('success', 'We have received your request for a loan.');
                $audit->log(
                    $user_id,
                    get_class($this),
                    __METHOD__,
                    'User successfully requested a loan.',
                    $ipAddr
                );

                return $this->redirect(['dashboard']);
            }

            $audit->log($user_id, get_class($this), __METHOD__, 'User request failed', $ipAddr);
            $session->setFlash('error', 'Cannot send request. An error was encountered.');
        }

        $complete = false;

        if ($this->client->hasCompleteProfile()) {
            $complete = true;
        }

        $this->viewPath = '@frontend/views/default';

        return $this->render('assistance', [
            'model' => $model,
            'tab' => $tab,
            'complete' => $complete,
        ]);
    }

    /**
     * Withdraw from cryptocurrency account balance
     * @param string $percent profile percentage completed
     * @param string $type type of account
     * @return Response|string view page
     */
    protected function withdrawToken(string $percent, string $type)
    {
        if ($percent < 90) {
            $this->session->addFlash('error', 'Profile must be 100% complete to withdraw your token');

            return $this->goBack($this->request->referrer);
        }

        $request = $this->request;
        $model = new WithdrawTokenForm();
        $model->initialize($this->user);

        if ($model->load($request->post())) {
            $model->sendEmail();
            $this->session->addFlash('success', 'Token withdrawal request submitted successful');

            return $this->redirect(["/{$type}/profile"]);
        }

        return $this->render('/default/withdraw', [
            'model' => $model,
            'countries' => Country::findAllCountriesWithName(),
        ]);
    }

    /**
     * Enables Two-Factor Authentication an existing User model.
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionEnableTwoFa()
    {
        $model = new TwoFaForm();
        $user = $this->findModel($this->user->id);

        if ($user->id !== $this->user->id) {
            throw new ForbiddenHttpException('You are not allowed to update this user.');
        }

        $url = '/individual/profile';

        if ($this->user->identity->business_user) {
            $url = '/business/ecosystem';
        }

        if ($user->hasTwoFaEnabled()) {
            $this->session->setFlash(
                'error',
                Yii::t(
                    'twofa',
                    'Two-Factor authentication is already enabled.'
                )
            );

            return $this->redirect([$url]);
        }

        $model->setUser($user);

        if ($model->load($this->request->post()) && $model->save()) {
            $this->session->setFlash(
                'success',
                Yii::t(
                    'twofa',
                    'Two-Factor authentication is enabled.'
                )
            );
            return $this->redirect([$url]);
        }

        return $this->render('enable-two-fa', ['model' => $model]);
    }

    /**
     * Enables Two-Factor Authentication an existing User model.
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionDisableTwoFa()
    {
        $user = $this->findModel($this->user->id);

        if ($user->id !== $this->user->id) {
            throw new ForbiddenHttpException('You are not allowed to update this user.');
        }

        $url = '/individual/profile';

        if ($this->user->identity->business_user) {
            $url = '/business/ecosystem';
        }

        if (!$user->hasTwoFaEnabled()) {
            $this->session->setFlash(
                'error',
                Yii::t(
                    'twofa',
                    'Two-Factor authentication is not enabled.'
                )
            );
        } else {
            $user->disableTwoFa();
            $this->session->setFlash(
                'success',
                Yii::t(
                    'twofa',
                    'Two-Factor authentication is disabled.'
                )
            );
        }

        return $this->redirect([$url]);
    }

    public function actionLoginVerification()
    {
        if (!$this->user->isGuest) {
            return $this->redirectToDashboard($this->user);
        }

        $user = $this->user->getIdentityFromLoginVerificationSession();

        if ($user === null) {
            $this->session->destroy();

            return $this->goHome();
        }

        $model = new TwoFaForm();
        $model->setScenario(TwoFaForm::SCENARIO_LOGIN);
        $model->setUser($user);

        if ($model->load($this->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('twofa-verification', ['model' => $model]);
    }

    public function actionProfileVerification()
    {
        if ($this->user->isGuest) {
            return $this->goHome();
        }

        $user = $this->user->getIdentityFromLoginVerificationSession();

        if ($user === null) {
            $this->session->remove($this->profileSessionKey);
            ;

            return $this->goHome();
        }

        $model = new ProfileTwoFaForm();
        $model->setUser($user);

        if ($model->load($this->request->post()) && $model->validate()) {
            $postData = $this->getDataFromProfileSession();
            $postData = $this->unsetUploadedFiles($postData);
            $this->client->scenario = 'update';
            $bankAccount = BankAccount::findAccount($this->client->id);
            $productService = ProductService::findProductService($this->client->id);
            $userBillRequest = UserBillRequest::findOne(['user_id' => $this->client->user_id]);
            $productService->category_id = $productService->findBusinessClientProductServiceAll($this->client->id);
            $subProductService = SubProductService::findSubProductService($productService->category_id);
            $subProductService->sub_category_id = $subProductService->findBusinessCategoryProductServiceAll($productService->category_id);

            if (!$userBillRequest) {
                $userBillRequest = $this->initClientBillRequests($this->client->user_id, $userBillRequest, $user->business_user);
            }

            if (
                $this->client->load($postData, 'BusinessClient')
                && $userBillRequest->load($postData, 'UserBillRequest')
                && $productService->load($postData, 'ProductService')
                && $subProductService->load($postData, 'SubProductService')
                && $bankAccount->load($postData, 'BankAccount')
            ) {
                if (
                    $this->client->validate()
                    && $userBillRequest->validate()
                    && $bankAccount->validate()
                ) {
                    if (
                        $this->client->updateProfileProgress()
                        && $bankAccount->save()
                        && $userBillRequest->saveBillRequest($this->client->user_id)
                        && $productService->saveProductService($this->client->id)
                        && $subProductService->saveSubProductService()
                    ) {
                        $this->session->remove($this->profileSessionKey);
                        $this->session->setFlash('success', 'Profile updated successfully');

                        return $this->redirect(['dashboard']);
                    }
                }
            }
        }

        return $this->render('twofa-verification', ['model' => $model]);
    }

    public function getDataFromProfileSession()
    {
        if ($this->hasProfileSession()) {
            $data = $this->session->get($this->profileSessionKey);

            $data = $data['data'] ?? [];

            if (!empty($data)) {
                return $data;
            }
        }

        $this->session->remove($this->profileSessionKey);

        return null;
    }

    protected function hasProfileSession(): bool
    {
        $data = $this->session->get($this->profileSessionKey);

        if ($data === null) {
            return false;
        }

        if (is_array($data) && count($data) > 0) {
            if (time() < $data['exp']) {
                return true;
            }
        }

        $this->session->remove($this->profileSessionKey);

        return false;
    }

    /**
     * @param array $postData
     * @return array
     */
    protected function unsetUploadedFiles(array $postData): array
    {
        if (isset($postData['BusinessClient'], $postData['BusinessClient']['business_logo'])) {
            unset($postData['BusinessClient']['business_logo']);
        }

        if (isset($postData['BusinessClient'], $postData['BusinessClient']['registration_document'])) {
            unset($postData['BusinessClient']['registration_document']);
        }

        return $postData;
    }

    /**
     * Finds the Payroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
