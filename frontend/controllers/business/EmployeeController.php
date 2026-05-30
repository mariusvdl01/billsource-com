<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\helpers\ArrayHelper;
use common\models\business\BusinessEmployee;
use common\models\business\BusinessEmployeeSearch;
use common\models\Province;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * EmployeeController implements the CRUD actions for BusinessEmployee model.
 */
class EmployeeController extends BusinessController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        ArrayHelper::merge([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ], $behaviors);

        return $behaviors;
    }

    /**
     * Lists all BusinessEmployee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusinessEmployeeSearch();
        $dataProvider = $searchModel->search($this->client->id, $this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BusinessEmployee model.
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
     * Creates a new BusinessEmployee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusinessEmployee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'provinces' => Province::findAllProvinces(),
                'business_id' => $this->client->id,
            ]);
        }
    }

    /**
     * Updates an existing BusinessEmployee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'provinces' => Province::findAllProvinces(),
                'business_id' => $model->business_id,
            ]);
        }
    }

    /**
     * Deletes an existing BusinessEmployee model.
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
     * Finds the BusinessEmployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessEmployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessEmployee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
