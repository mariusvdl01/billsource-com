<?php 

namespace frontend\controllers\individual;

class SocialController extends \common\controllers\IndividualController
{
	public function actions()
	{
		return parent::actions();
	}

	public function actionInvite()
	{
		return $this->render('invite');
	}
	
	public function actionConnect()
	{
		return $this->render('connect');
	}
	
	public function actionPosts()
	{
		return $this->render('post');
	}
}
?>