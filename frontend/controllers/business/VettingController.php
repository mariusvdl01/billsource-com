<?php 

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use frontend\models\VettingForm;
use yii\filters\AccessControl;

class VettingController extends BusinessController
{
	/**
	 * Defines the business user layout for the entire site
	 *
	 * @var string $layout business layout
	 */
	//public $layout = 'business/main';
	/**
     *
     * @var string $defaultAction
	 */
	public $defaultAction = 'vet';
	
	/**
	 * Defines behaviors to attach to actions in this class.
	 *
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
					[
						'allow' => false,
						'roles' => ['*'],
					]
				],
			],
		];
	}
	
	public function actionVet()
	{
		$model = new VettingForm;
		
		if($model->load($this->request->Post()) && $model->validate())
			if($model->vetBusiness()) {
				$this->session->setFlash('isVetted');
			} else {
				$this->session->setFlash('isNotVetted');
			}
		
		return $this->render('vetting', [
			'model' => $model,
		]);
	}
}
