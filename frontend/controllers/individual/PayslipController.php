<?php

namespace frontend\controllers\individual;

use common\models\invoice\InvoiceSearch;
use common\models\invoice\Payslip;

/**
 * The controller class for payslips belonging to user.
 * 
 * @author Kenneth Onah
 *
 */
class PayslipController extends \common\controllers\IndividualController
{
	public function actions()
	{
		return parent::actions();
	}
    
    /**
     * Renders paid invoices.
     *
     */
    public function actionPaid()
    {
        $type = Payslip::TYPE_PAYSLIP;
        $paid = Payslip::INVOICE_PAID;
        $status = Payslip::STATUS_SENT;
    	$searchModel = new InvoiceSearch();
    	$dataProvider = $searchModel->searchForIndividual(
    	    $this->userId,
            $this->request->queryParams,
            $paid, $status, $type
        );
    	return $this->render('paid', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
}
