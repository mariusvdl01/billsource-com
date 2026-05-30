<?php

namespace frontend\controllers\business;

use Yii;
use common\models\collector\CollectorsBin;
use common\models\collector\BinSearch;
use common\controllers\BusinessController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CollectorController implements the CRUD actions for CollectorsBin model.
 */
class CollectorController extends BusinessController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all CollectorsBin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $searchModel = new BinSearch();
        $dataProvider = $searchModel->search($request->queryParams);
        
        if($request->isPost) {
            if(!empty($request->post('invoice_ids'))) {
                $this->session->set('invoice.ids', $request->post('invoice_ids'));
                return $this->redirect(['/payment']);
            }
            $session->setFlash('error', 'Please select at least one invoice to factor');
        }

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CollectorsBin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
    public function actionCreate()
    {
        $model = new CollectorsBin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bin_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    */

    /**
     * Finds the CollectorsBin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CollectorsBin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    protected function findModel($id)
    {
        if (($model = CollectorsBin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    */
}
