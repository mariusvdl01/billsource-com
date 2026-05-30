<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/18/15
 * Time: 10:07 PM
 */

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\AuditTrail;

class AbstractBaseController extends ActiveController
{
	protected $request;
	protected $formatter;
	/**
     * An instance of the audit logger
     *
     * @var AuditTrail $audit | This property is read-only
     */
    protected $audit;

	public function initResponseHeaders()
	{
	    Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', '*');
	    Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Methods', 'GET, POST, DELETE, PATCH');
	    Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Headers', 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
	    parent::init();
	}

	public function beforeAction($action) {
		if (parent::beforeAction($action)) {
			$this->setAppVariables();
			return true;
		} else {
			return false;
		}
	}

	private function setAppVariables() {
		$this->initRequest();
		$this->initFormatter();
		$this->initResponseHeaders();
		$this->initAuditTrailInstance();
	}

	private function initRequest() {
		if(!$this->request) {
			$this->request = Yii::$app->getRequest();
		}
	}

	private function initFormatter() {
		if(!$this->formatter) {
			$this->formatter = Yii::$app->formatter;
		}
	}

	private function initAuditTrailInstance()
    {
        if (!$this->audit) {
            $this->audit = new AuditTrail;
        }
    }
}