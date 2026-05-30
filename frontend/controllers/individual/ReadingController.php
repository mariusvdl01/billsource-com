<?php

namespace frontend\controllers\individual;

use common\controllers\IndividualController;
use common\models\individual\IndividualReading;
use common\models\individual\IndividualReadingSearch;
use common\models\Reading;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * ReadingController implements the CRUD actions for IndividualReading model.
 */
class ReadingController extends IndividualController
{
    public function actions()
    {
        return parent::actions();
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all IndividualReading models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$id = $this->userId;
        $searchModel = new IndividualReadingSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IndividualReading model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new IndividualReading model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	$request = $this->request;
    	$user_id = $this->userId;
    	$read = new Reading;
    	$model = new IndividualReading();
		$reading = Reading::findAllUtilities();
		
		if($request->isPost){
	        if ($model->load($request->post(), 'IndividualReading') && $model->saveReadings($user_id)) {
	            return $this->redirect(['index']);
	        } 
		}
        return $this->render('create', [
        		'model' => $model,
        		'read' => $read,
        		'reading' => $reading,
        ]);
    }

    /**
     * Updates an existing IndividualReading model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$request = $this->request;
    	$user_id = $this->userId;
    	$model = $this->findModel($id);
        $read = new Reading;
        $reading = Reading::findAllUtilities();
        
        if ($model->load($request->post(), 'IndividualReading') && $model->saveReadings($user_id)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } 
        
        //$read->reading_id = 
        return $this->render('update', [
        	'model' => $model,
        	'read' => $read,
           	'reading' => $reading,
        ]);
    }

    /**
     * Deletes an existing IndividualReading model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the IndividualReading model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IndividualReading the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IndividualReading::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
