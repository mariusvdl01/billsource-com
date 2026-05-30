<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\events\BillEvent;
use common\helpers\ArrayHelper;
use common\models\business\BusinessClient;
use common\models\business\BusinessEmployee;
use common\models\catalog\Product;
use common\models\document\BillerDocumentFactory;
use common\models\invoice\InvoiceLineManager;
use common\models\invoice\InvoiceLog;
use common\models\invoice\Payslip;
use common\models\invoice\PayslipSearch;
use common\models\Status;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * PayslipController implements the CRUD actions for Payslip model.
 */
class PayslipController extends BusinessController
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
     * Lists all Payslip models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayslipSearch();
        $dataProvider = $searchModel->search($this->client, $this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Payroll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $audit = $this->audit;
        $request = $this->request;
        $ip = $request->getUserIP();
        $factory = new BillerDocumentFactory;
        $payslip = $factory->makePayslip();
        $lineManager = new InvoiceLineManager($payslip);
        $biller = BusinessClient::findOne(['user_id' => $this->userId]);
        $employees = BusinessEmployee::getEmployees($biller->id);
        $payslip->business_id = $biller->id;

        if($request->isPost) {
            if(!InvoiceLog::canCreateBill($biller->id)) {
                $this->session->setFlash('error',
                    'You have reached your maximum number of payslips for the month! Please contact customer care');
                return $this->redirect(['index']);
            }
            $payslip->load($request->Post(), 'Payslip');
            $this->manageCustomer($request->post('employee-select'), $payslip, $biller);
            if($payslip->validate() &&  $payslip->save(false)) {
                $lineManager->manage($payslip, $request->post('InvoiceLine'));
                if($lineManager->validateLineItems() && $lineManager->saveLineItems($payslip)) {
                    InvoiceLog::replaceClientInvoiceLog($biller->id);
                    $audit->log($this->userId, $this->action->uniqueId, 'NewPayslip', 'Successfully sent new payslip', $ip);
                    $this->session->setFlash('success', 'Payslip created and saved successfully');
                    $event = new BillEvent();
                    $event->biller = $biller;
                    $event->audit = $audit;
                    $payslip->trigger(BillEvent::BILL_NEW, $event);
                    return $this->redirect(['index']);
                }
            } else {
                $audit->log($this->userId, $this->action->uniqueId, 'NewPayslip', 'Error while sending new payslip', $ip);
            }
        }

        $payslip->reference_number = $this->referenceNumberGenerator();
        return $this->render('create', [
            'biller'		=> $biller,
            'employees'		=> $employees,
            'statuses'      => array(),
            'payslip' 		=> $payslip,
            'lineManager'	=> $lineManager,
            'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
     * Updates an existing Payroll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $payslip = Payslip::findOne($id);
        $lineManager = new InvoiceLineManager($payslip);
        $biller = BusinessClient::findOne(['user_id' => $this->userId]);
        $employees = BusinessEmployee::getEmployees($biller->id);
        $statuses = Status::findAllStatuses();

        if($this->request->isPost) {
            $payslip->load($this->request->Post(), 'Payslip');
            if($payslip->validate())
            {
                if($payslip->status_id == Payslip::STATUS_PAID) {
                    $payslip->paid = Payslip::INVOICE_PAID;
                    $payslip->savePaymentInfo();
                }
                $payslip->save(false);
                $lineManager->manage($payslip, $this->request->Post('InvoiceLine'));
                $lineManager->validate();
                $lineManager->saveLineItems($payslip);

                $this->session->setFlash('success', 'Payslip updated successfully');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'biller'		=> $biller,
            'employees'		=> $employees,
            'payslip' 		=> $payslip,
            'lineManager'	=> $lineManager,
            'statuses'		=> $statuses,
            'products'		=> Product::findAvailableProduct($this->client),
            'terms'         => $this->getCreditTerms(),
        ]);
    }

    /**
     * Deletes an existing Payroll model.
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
     * Finds the Payroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payslip the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payslip::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
