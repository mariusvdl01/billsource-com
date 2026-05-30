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
use frontend\models\SignupForm;
use frontend\models\UserLoginForm;
use frontend\models\WithdrawTokenForm;
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
 */
class BaseController extends Controller
{
    /** @var AssetBundle $assetBundle */
    protected $assetBundle = null;

    /** @var IndividualClient|BusinessClient $client */
    protected $client = null;

    /** @var LoginForm */
    protected $loginForm;

    /** @var SignupForm */
    protected $signupForm;

    /** @var integer */
    protected $userId;

    /** @var User */
    protected $user;

    /** @var Session */
    protected $session;

    /**
     * PHP 8.2 fix: $request must be public to match yii\base\Controller visibility
     * @var Request
     */
    public $request;

    /**
     * PHP 8.2 fix: $response must be public to match yii\base\Controller visibility
     * @var Response
     */
    public $response;

    /** @var AuditTrail */
    protected $audit;

    /** @var array */
    protected $unreadBillsCounter;

    /** @var string */
    protected $profileSessionKey = 'profile';

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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!Yii::$app->user->isGuest) {
                $user = Yii::$app->user->identity;
                if (method_exists($user, 'isTrialExpired') && $user->isTrialExpired()
                    && isset($user->client) && !$user->client->is_subscribed) {
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

    protected function initLoginForm()
    {
        if (!$this->loginForm) {
            $this->loginForm = new UserLoginForm();
            Yii::$app->params['model'] = $this->loginForm;
        } else {
            Yii::$app->params['model'] = $this->loginForm;
        }
    }

    protected function initSignupForm()
    {
        if (!$this->signupForm) {
            $this->signupForm = new SignupForm();
            Yii::$app->params['signupForm'] = $this->signupForm;
        } else {
            Yii::$app->params['signupForm'] = $this->signupForm;
        }
    }

    protected function initUser()
    {
        if (!$this->user) {
            $this->user = Yii::$app->user;
        }
    }

    protected function initUserId()
    {
        if (!$this->userId) {
            $this->userId = Yii::$app->session['__id'];
        }
    }

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

    protected function initAuditTrailInstance()
    {
        if (!$this->audit) {
            $this->audit = new AuditTrail();
        }
    }

    protected function loadUserRoleById()
    {
        $id = $this->userId;
        $auth = Yii::$app->authManager;

        if ($auth) {
            Yii::$app->params['__role'] = key($auth->getRolesByUser($id));
        }
    }

    public function loadAssetBundle()
    {
        if (!$this->assetBundle) {
            $view = $this->getView();
            $this->setJavaScriptVariable($view);
            $this->assetBundle = $view->registerAssetBundle(BillsourceAsset::class);
            Yii::$app->params['assetBundle'] = $this->assetBundle;
        }
    }

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

    protected function setJavaScriptVariable(View $view)
    {
        $view->registerJsVar('tax_rate', Yii::$app->params['tax_rate']);
    }

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

    protected function renderVaultedPdfFile($invoice)
    {
        $path = Yii::getAlias(Vault::VAULT_DIR);
        $pdfFile = $path . DIRECTORY_SEPARATOR . $invoice['pdf'];

        if (file_exists($pdfFile)) {
            $this->response->sendFile($pdfFile, $invoice['reference_number'] . '.pdf');
            $this->response->send();
        }
    }

    public function getClient()
    {
        return $this->client ?? null;
    }

    protected function preDispatch()
    {
        $user = Yii::$app->user;

        if ($user->isGuest) {
            return $this->redirect(['/account/login']);
        }

        return $this->redirectToDashboard($user);
    }

    protected function setUserTheme()
    {
        $user = $this->user->identity;

        if ($user->business_user) {
            $this->layout = '//business/main';
        } else {
            $this->layout = '//individual/main';
        }
    }

    protected function checkPermission($permission, $client)
    {
        if (!$this->user->can($permission, ['client' => $client])) {
            throw new ForbiddenHttpException('You are not authorized to perform this action', 403);
        }
    }

    protected function initClientBillRequests($id, $billRequest, $type)
    {
        if (!isset($billRequest)) {
            $billRequest = new UserBillRequest();
            $billRequest->user_id = $id;
            $billRequest->is_business_user = $type;
        }

        return $billRequest;
    }

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
                    $audit->log($user_id, get_class($this), __METHOD__, 'User successfully requested assistance.', $ipAddr);
                } else {
                    $session->setFlash('info', 'You have previously submitted a request for assistance.');
                }
                return $this->redirect(['dashboard']);
            }

            if ($request->post('loan') && $model->submitLoanRequest($user_id)) {
                $session->setFlash('success', 'We have received your request for a loan.');
                $audit->log($user_id, get_class($this), __METHOD__, 'User successfully requested a loan.', $ipAddr);
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

    // ── 2FA ACTIONS STUBBED — promocat/twofa not available on Packagist ──────
    // These will be rebuilt using spomky-labs/otphp in Phase 2
    // ─────────────────────────────────────────────────────────────────────────

    public function actionEnableTwoFa()
    {
        Yii::$app->session->setFlash('info', 'Two-Factor Authentication setup is coming soon.');
        return $this->redirect(['/business/ecosystem']);
    }

    public function actionDisableTwoFa()
    {
        Yii::$app->session->setFlash('info', 'Two-Factor Authentication management is coming soon.');
        return $this->redirect(['/business/ecosystem']);
    }

    public function actionLoginVerification()
    {
        return $this->goHome();
    }

    public function actionProfileVerification()
    {
        return $this->goHome();
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
