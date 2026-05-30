<?php 

namespace frontend\controllers\business;

use common\Registry;
use common\models\User;
use common\models\Title;
use common\models\Province;
use common\models\business\BillerForm;
use common\models\business\BillerSearch;
use common\models\business\BusinessClient;
use common\controllers\BusinessController;

class BillerController extends BusinessController
{
	public function actionIndex()
	{
		$searchModel = new BillerSearch();
		$dataProvider = $searchModel->search($this->userId, $this->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionCreate()
	{
		$model = new BillerForm;
		$model->scenario = 'default';

		$id = $this->client->business_id;
		if($model->load($this->request->post()) && $model->validate())
		{
			$model->setClient($this->client);
			$model->setParentId($id);
			Registry::register('user', $this->user->identity);
			if($model->saveBiller()) {
				$this->session->setFlash('success', 'New biller company created successfully');
				return $this->redirect(['index']);
			}
			$this->session->setFlash('error', 'Server error encountered. Please contact administrator');
		}
		
		return $this->render('create', [
			'model'	=> $model,
			'titles' => Title::findAllTitles(),
			'provinces' => Province::findAllProvinces()
		]);
	}
	
	public function actionUpdate($id)
	{
		$user = $this->findModel($id);
		$form = new BillerForm;
		$form->scenario = 'update';
		
		if($form->load($this->request->post()) && $form->validate()) {
			$form->setUser($user);
			$form->setClient($this->client);
			if(($result = $form->saveBiller()) !== false) {
				$this->session->setFlash('success', 'Company details updated successfully');
				return $this->redirect(['index']);
			}
			$this->session->setFlash('error', array_shift($result));
		}

		$this->loadAttributesToForm($user, $form);
		return $this->render('update', [
			'model' => $form,
			'titles' => Title::findAllTitles(),
			'provinces' => Province::findAllProvinces()
		]);
	}
	/*
	public function actionDelete($id)
	{
		$user = User::findOne(['user_id' => $id]);
		if($user) {
			$user->delete();
			$this->session->setFlash('success', 'User deleted successfully');
		} else {
			$this->session->setFlash('error', 'An error was encountered. Please contact administrator');
		}
		
		return $this->redirect('index');
	}
    */
	protected function findModel($user_id)
	{
		if(($model = User::find()->joinWith('businessClient', true, 'INNER JOIN')
			->where((['user.user_id' => $user_id]))->one()) !== null) {
			return $model;
		} else {
			throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
		}
	}

	protected function loadAttributesToForm($user, &$form)
	{
		if($user) {
			$client = BusinessClient::findOne(['user_id' => $user->user_id]);
            $form->active  = $user->status;
			$form->setAttributes($client->getAttributes());
		}
	}

    public function actionSwitchUser()
    {
        $user = null;
        $request = $this->request;
        $originalId = $this->session->get('user.idbeforeswitch');
        if($request->isAjax) {
            $id = $request->post('user_id');
            $initialId = $this->userId; //here is the current ID, so you can go back after that.
            if ($id == $initialId) {
                return $this->redirectToDashboard($this->user);
            } elseif($id == $originalId) {
                $user = User::findOne($originalId);
                $duration = 0;
                $this->user->switchIdentity($user, $duration);
                $this->session->remove('user.idbeforeswitch');
            } else {
                $user = User::findOne($id);
                $duration = 0;
                $this->user->switchIdentity($user, $duration); //Change the current user.
                $this->session->set('user.idbeforeswitch', $initialId); //Save in the session the id of your admin user.
            }
            return $this->redirectToDashboard($user);
        }
    }
}