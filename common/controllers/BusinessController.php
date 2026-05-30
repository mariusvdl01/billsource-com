<?php

namespace common\controllers;

use common\helpers\ArrayHelper;
use common\helpers\Billsource;
use common\models\business\BusinessClient as Client;
use common\models\business\BusinessClientCrm as Crm;
use common\models\business\BusinessEmployee;
use common\models\BusinessProfile as Profile;
use common\models\catalog\Product;
use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\web\Response;

class BusinessController extends BaseController
{
    /**
     * Defines how certain actions can be executed irrespective of the default behavior
     * @return array
     */
    public function actions()
    {
        return parent::actions();
    }

    /**
     * Defines behaviors to attach to actions in this class.
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        ArrayHelper::merge([
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['reader', 'loader', 'singleUserAdmin', 'businessAdmin'],
                    ],
                ],
            ],
        ], $behaviors);

        return $behaviors;
    }

    /**
     * Handles beforeAction event trigger before controller actions are executed
     * @param Action $action current action being executed
     * @return boolean
     * @throws \yii\db\Exception
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->initBusinessClient();
            $this->initBillers();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Initialize business client object
     */
    private function initBusinessClient()
    {
        if (is_null($this->client)) {
            if ($this->user->identity->business_user) {
                $this->client = Client::findIdentity($this->userId);
            }
        }

        Yii::$app->params['client'] = $this->client;
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function initBillers()
    {
        $idBeforeSwitch = $this->session['user.idbeforeswitch'];
        $id = !empty($idBeforeSwitch) ? $idBeforeSwitch : $this->userId;
        $billers = Yii::$app->db->createCommand(
            Client::findBillers(), [
                ':id' => $id
            ]
        )->queryAll();
        $data = ArrayHelper::map($billers, 'user_id', 'trading_name');
        Yii::$app->params['data'] = $data;
    }

    /**
     * @return array
     */
    public function actionCustomerData()
    {
        if ($this->request->isAjax) {
            $data = $this->request->post();
            $crm_id = $data['id'];
            $customer = Crm::getCustomerData($crm_id);
            $this->response->format = Response::FORMAT_JSON;

            return [
                'body' => $customer,
                'success' => true,
            ];
        }

        return [
            'body' => '',
            'success' => false,
        ];
    }

    /**
     * @return array
     */
    public function actionProductData()
    {
        $this->response->format = Response::FORMAT_JSON;

        if ($this->request->isAjax) {
            $data = $this->request->post();
            $productId = $data['id'];
            $product = Product::find()->where('[[id]]=:id', ['id' => $productId])->one();

            return [
                'data' => $product,
                'success' => true,
            ];
        }

        return [
            'data' => '',
            'success' => false,
        ];
    }

    /**
     * @return array
     */
    public function actionEmployeeData()
    {
        if ($this->request->isAjax) {
            $data = $this->request->post();
            $emp_id = $data['id'];
            $employee = BusinessEmployee::getEmployeeData($emp_id);
            $this->response->format = Response::FORMAT_JSON;

            return [
                'body' => $employee,
                'success' => true,
            ];
        }

        return [
            'body' => '',
            'success' => false,
        ];
    }

    /**
     * @return string
     */
    protected function referenceNumberGenerator()
    {
        return Billsource::getReferenceNumber($this->id);
    }

    /**
     * @param $id
     * @param $invoice
     * @param $biller
     */
    protected function manageCustomer($id, $invoice, $biller)
    {
        $customer = new Crm();
        $existingCustomer = Crm::findOne(['id', $id]);

        if (!$existingCustomer) {
            $customer->insertNewCustomer($invoice, $biller);
        } else {
            $customer->incrementUses($id, $invoice, $biller);
        }
    }

    /**
     * @return array
     */
    protected function getProfiles()
    {
        $profiles = Profile::findAllProfiles();

        if ($this->client->profile_id != Client::PROFILE_FREE) {
            foreach ($profiles as $profile) {
                unset($profiles[Client::PROFILE_FREE]);
            }
        }

        return $profiles;
    }

      /**
     * @return array
     */
    protected function getProfilesById($id)
    {
        $profile = Profile::findProfileById($id);
        return $profile;
    }

    /**
     * @return string[]
     */
    protected function getCreditTerms()
    {
        return [
            'cod' => 'COD',
            '30 days' => '30 days'
        ];
    }
}