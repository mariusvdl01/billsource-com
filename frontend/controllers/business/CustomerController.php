<?php

namespace frontend\controllers\business;

use common\models\business\BusinessClientCrm;
use common\models\business\CustomerSearch;
use common\models\Province;
use Yii;


/**
 * Controller class containing logic for all actions/process in managing customers
 * 
 * @author Kenneth Onah
 *
 */
class CustomerController extends \common\controllers\BusinessController
{	
	/**
	 * TODO
	 */
    public function actionCreate()
    {
    	$customer = new BusinessClientCrm;
 
    	if($customer->load(Yii::$app->request->post()) && $customer->validate()) {
    		if($customer->save()) {
    			$this->session->setFlash('success', 'Customer added successfully');
    			$this->redirect(['index']);
    		}
    	}
    	
        return $this->render('create', [
        	'model' => $customer,
        	'provinces' => Province::findAllProvinces(),
        	'business_id' => $this->client->id,
        ]);
    }

    public function actionView($id) 
    {
    	$model = BusinessClientCrm::findOne($id);
    	$this->redirect(['view'], ['model' => $model]);
    }
    
    /**
	 * TODO
	 */
    public function actionDelete($id)
    {
    	$customer = BusinessClientCrm::findOne($id);
    	if(isset($customer) && $customer) {
    		$customer->deleted = BusinessClientCrm::CUSTOMER_DELETED;
    		$customer->save(false);
    		$this->session->setFlash('success', 'Customer deleted successfully');
    	} else {
    			$this->session->setFlash('error', 'An error was encountered.');
    	}
    	return $this->redirect(['index']);
    }

    /**
	 * TODO
	 */
    public function actionUpdate($id)
    {
    	$customer = BusinessClientCrm::findOne($id);
    	
    	if($customer->load(Yii::$app->request->post()) && $customer->validate()) {
    		if($customer->save()) {
    			$this->session->setFlash('success', 'Customer details updated successfully');
    			$this->redirect(['index']);
    		}
    	}
        return $this->render('update', [
        	'model' => $customer,
        	'provinces' => Province::findAllProvinces(),
        	'business_id' => $this->client->id,
        ]);
    }

    /**
     * Renders debtors page.
     *
     * @return string $view home page view script.
     */
    public function actionIndex()
    {
    	$searchModel = new CustomerSearch();
    	$dataProvider = $searchModel->search($this->userId, $this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
