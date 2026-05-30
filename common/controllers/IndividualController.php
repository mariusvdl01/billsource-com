<?php

namespace common\controllers;

use Yii;
use yii\filters\AccessControl;
use common\helpers\ArrayHelper;
use common\models\individual\IndividualClient as Client;
use common\controllers\BaseController;

class IndividualController extends BaseController
{
    /**
     * Defines the business user layout for the entire site
     *
     * @var string $layout business layout
     */
    //public $layout = 'individual/main';

    public $defaultAction = 'dashboard';

    /**
     * Defines how certain actions can be executed irrespective of the default behavior
     *
     * @return array $actions defines actions behavior
     */
    public function actions()
    {
        return parent::actions();
    }

    /**
     * Defines behaviors to attach to actions in this class.
     *
     * @return array $behaviors an array of behaviors
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
                        'roles' => ['individual'],
                    ],
                ],
            ],
        ], $behaviors);

        return $behaviors;
    }

    /**
     * Handles beforeAction event trigger before controller actions are executed
     *
     * @param bool $action current action being executed
     *
     * @return boolean true|false if action is executed successfully returns true otherwise false
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->initIndividualClient();

            return true;
        } else {
            return false;
        }
    }

    private function initIndividualClient()
    {
        if (is_null($this->client)) {
            if(!$this->user->identity->business_user)
                $this->client = Client::findIdentity($this->userId);
        }
        Yii::$app->params['client'] = $this->client;
    }
}