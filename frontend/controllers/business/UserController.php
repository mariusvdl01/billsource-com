<?php 

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\models\business\BusinessClient;
use common\models\business\UserForm;
use common\models\business\UserSearch;
use common\models\User;

class UserController extends BusinessController
{
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search($this->userId, $this->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionCreate()
	{
		$model = new UserForm;
		
		if($model->load($this->request->post()) && $model->validate())
		{
			$id = BusinessClient::findOne(['user_id'=>$this->userId])->id;
			$model->setClient($this->client);
			$model->setParentId($id);
			if($model->saveUser()) {
				$this->session->setFlash('success', 'New user created successfully');
				return $this->redirect(['index']);
			}
			$this->session->setFlash('error', 'Server error encountered. Please contact administrator');
		}
		
		return $this->render('create', [
			'model'	=> $model,
		]);
	}
	
	public function actionUpdate($id)
	{
		$user = $this->findModel($id);
		$form = new UserForm;
		$form->scenario = 'update';
		
		if($form->load($this->request->post()) && $form->validate()) {
			$form->setUser($user);
			$form->setClient($this->client);
			if(($result = $form->saveUser()) !== false) {
				$this->session->setFlash('success', 'User details updated successfully');
				return $this->redirect('index');
			}
			$this->session->setFlash('error', array_shift($result));
		}
		$form = $this->loadAttributesToForm($user, $form);
		return $this->render('update', [
			'model' => $form,
		]);
	}
	
	public function actionDelete($id)
	{
		$user = User::findOne(['id' => $id]);
		if($user) {
			$user->delete();
			$this->session->setFlash('success', 'User deleted successfully');
		} else {
			$this->session->setFlash('error', 'An error was encountered. Please contact administrator');
		}
		
		return $this->redirect('index');
	}

	protected function findModel($user_id)
	{
		if(($model = User::find()->joinWith('businessClient', true, 'INNER JOIN')
			->where((['user.id' => $user_id]))->one()) !== null) {
			return $model;
		} else {
			throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
		}
	}

	protected function loadAttributesToForm($user, $form)
	{
		if($user) {
			$form->full_name = $user->businessClient->contact_person;
			$form->email = $user->email;
			$form->active = $user->status;
			$form->role = key(\Yii::$app->getAuthManager()->getAssignments($user->id));
			return $form;
		}
	}
}