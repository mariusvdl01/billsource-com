<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\user\LoginForm;
use yii\web\Controller;

/**
 * Dashboard controller
 */
class DashboardController extends Controller
{
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

    public function actionIndex()
    {
        return $this->render('index');
    }
}
