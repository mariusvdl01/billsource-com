<?php

namespace frontend\controllers\individual;

use common\models\invoice\InvoiceSearch;
use common\models\invoice\Ticket;
use common\models\Status;
use yii\web\NotFoundHttpException;

/**
 * The controller class for tickets belonging to user.
 * 
 * @author Kenneth Onah
 *
 */
class TicketController extends \common\controllers\IndividualController
{
	public function actions()
	{
		return parent::actions();
	}
    
    /**
     * Renders tickets in planning state.
     *
     */
    public function actionPlanning()
    {
        $type = Ticket::TYPE_TICKET;
        $paid = Ticket::INVOICE_PAID;
        $status = Status::findOne(['code' => Status::STATUS_PLANNING]);
        $status_id = $status->id;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchForIndividual(
    	    $this->userId,
            $this->request->queryParams,
            $paid, $status_id, $type);
    	return $this->render('planning', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }

    /**
     * Renders tickets in processing state.
     *
     */
    public function actionProcessing()
    {
        $type = Ticket::TYPE_TICKET;
        $paid = Ticket::INVOICE_PAID;
        $status = Status::findOne(['code' => Status::STATUS_PROCESSING]);
        $status_id = $status->id;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->searchForIndividual(
            $this->userId,
            $this->request->queryParams,
            $paid, $status_id, $type);
        return $this->render('processing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Renders tickets in finalized state.
     *
     */
    public function actionFinalized()
    {
        $type = Ticket::TYPE_TICKET;
        $paid = Ticket::INVOICE_PAID;
        $status = Status::findOne(['code' => Status::STATUS_FINALIZED]);
        $status_id = $status->id;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->searchForIndividual(
            $this->userId,
            $this->request->queryParams,
            $paid, $status_id, $type);
        return $this->render('finalized', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Renders tickets in completed state.
     *
     */
    public function actionCompleted()
    {
        $type = Ticket::TYPE_TICKET;
        $paid = Ticket::INVOICE_PAID;
        $status = Status::findOne(['code' => Status::STATUS_COMPLETED]);
        $status_id = $status->id;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->searchForIndividual(
            $this->userId,
            $this->request->queryParams,
            $paid, $status_id, $type);
        return $this->render('completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ticket model.
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
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
