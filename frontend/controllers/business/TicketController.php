<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\events\BillEvent;
use common\helpers\ArrayHelper;
use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm as Crm;
use common\models\catalog\Product;
use common\models\document\BillerDocumentFactory;
use common\models\invoice\InvoiceLineManager;
use common\models\invoice\InvoiceLog;
use common\models\invoice\Ticket;
use common\models\invoice\TicketSearch;
use common\models\Status;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends BusinessController
{
    public $defaultAction = 'planning';

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
     * Lists all Ticket models in planning.
     * @return mixed
     */
    public function actionPlanning()
    {
        $status = Status::findOne(['code' => Status::STATUS_PLANNING]);
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->searchTickets($this->client, $this->request->queryParams, $status->id);

        return $this->render('planning', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Ticket models in process.
     * @return mixed
     */
    public function actionProcessing()
    {
        $status = Status::findOne(['code' => Status::STATUS_PROCESSING]);
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->searchTickets($this->client, $this->request->queryParams, $status->id);

        return $this->render('processing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Ticket models in finalized.
     * @return mixed
     */
    public function actionFinalized()
    {
        $status = Status::findOne(['code' => Status::STATUS_FINALIZED]);
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->searchTickets($this->client, $this->request->queryParams, $status->id);

        return $this->render('finalized', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Ticket models in completed.
     * @return mixed
     */
    public function actionCompleted()
    {
        $status = Status::findOne(['code' => Status::STATUS_COMPLETED]);
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->searchTickets($this->client, $this->request->queryParams, $status->id);

        return $this->render('completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $audit = $this->audit;
        $request = $this->request;
        $ip = $request->getUserIP();
        $factory = new BillerDocumentFactory;
        $ticket = $factory->makeTicket();
        $lineManager = new InvoiceLineManager($ticket);
        $biller = BusinessClient::findOne(['user_id' => $this->userId]);
        $customers = \Yii::createObject(Crm::className())->findAllByBusinessId($biller->id);
        $ticket->business_id = $biller->id;

        if($request->isPost) {
            if(!InvoiceLog::canCreateBill($biller->id)) {
                $this->session->setFlash('error',
                    'You have reached your maximum number of tickets for the month! Please contact customer care');

                return $this->redirect(['index']);
            }
            $ticket->load($request->Post(), 'Ticket');
            $this->manageCustomer($request->post('crm_id'), $ticket, $biller);
            if($ticket->validate() &&  $ticket->save(false)) {
                $lineManager->manage($ticket, $request->post('InvoiceLine'));
                if($lineManager->validateLineItems() && $lineManager->saveLineItems($ticket)) {
                    $audit->log($this->userId, $this->action->uniqueId, 'NewTicket', 'Successfully saved new ticket', $ip);
                    $this->session->setFlash('success', 'Ticket created and saved successfully');
                    $event = new BillEvent();
                    $event->biller = $biller;
                    $event->audit = $audit;
                    $ticket->trigger(BillEvent::BILL_NEW, $event);

                    return $this->redirect(['view', 'id' => $ticket->getPrimaryKey()]);
                } else {
                    $this->session->setFlash('error', 'Error while saving tickets');
                }
            } else {
                $audit->log($this->userId, $this->action->uniqueId, 'NewTicket', 'Error while saving new ticket', $ip);
            }
        }

        $ticket->reference_number = $this->referenceNumberGenerator();

        return $this->render('create', [
            'biller'		=> $biller,
            'customers'		=> $customers,
            'statuses'      => Status::findTicketStatuses(),
            'ticket' 		=> $ticket,
            'lineManager'	=> $lineManager,
            'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $ticket = Ticket::findOne($id);
        $lineManager = new InvoiceLineManager($ticket);
        $biller = BusinessClient::findOne(['user_id' => $this->userId]);
        $customers = \Yii::createObject(Crm::className())->findAllByBusinessId($biller->id);

        if($this->request->isPost) {
            $ticket->load($this->request->Post(), 'Ticket');
            if($ticket->validate())
            {
                $ticket->save(false);
                $lineManager->manage($ticket, $this->request->Post('InvoiceLine'));
                $lineManager->validate();
                $lineManager->saveLineItems($ticket);
                $this->session->setFlash('success', 'Ticket updated successfully');
                return $this->redirect(['view', 'id' => $ticket->getPrimaryKey()]);
            }
        }

        return $this->render('update', [
            'biller'		=> $biller,
            'customers'		=> $customers,
            'ticket' 		=> $ticket,
            'lineManager'	=> $lineManager,
            'statuses'		=> Status::findTicketStatuses(),
            'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'planning' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $url = str_replace('ticket_', '', $model->status->code);
        $model->delete();
        return $this->redirect([$url]);
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
}
