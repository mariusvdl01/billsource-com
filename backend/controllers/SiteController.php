<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;

use backend\models\user\LoginForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
	public $defaultAction = 'login';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['/dashboard']);
        }
        $this->layout = '//main-login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/dashboard']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
