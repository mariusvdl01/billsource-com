<?php 

namespace frontend\controllers\individual;

use Yii;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Status;
use common\models\individual\Vault;

class VaultController extends \common\controllers\IndividualController
{
	public $defaultAction = 'create';

	public function actions()
	{
		return parent::actions();
	}

	public function actionCreate()
	{
		$model = new Vault();
		
		if ($model->load(Yii::$app->request->post())) {
			$model->invoice_file = UploadedFile::getInstance($model, 'invoice_file');
			if ($model->upload($this->userId)) {
				$this->session->setFlash('success', 'Invoice saved in your vault successfully');
				return $this->refresh();
			}
			$this->session->setFlash('error', 'Invoice already exists in the vault');
			return $this->refresh();
		}
		
		return $this->render('create', [
			'model' => $model,
			'statuses' => Status::findAllStatuses(),
		]);
	}
}