<?php

namespace frontend\controllers\business\catalog;

use common\controllers\BusinessController;
use common\models\catalog\Category;
use common\models\catalog\Product;
use common\models\catalog\ProductSearch;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * Controller class contining logic for all actions/process in invoice module
 * 
 * @author Kenneth Onah
 *
 */
class ProductController extends BusinessController
{	
	
	/**
	 * TODO
	 */
    public function actionIndex()
    {    	
       $searchModel = new ProductSearch;
       $request = $this->request;
       $dataProvider = $searchModel->search($request->getQueryParams());
       
       // validate if there is a editable input saved via AJAX
       if ($request->post('hasEditable')) {
       	// instantiate your book model for saving
       	$bookId = $request->post('editableKey');
       	$model = $this->findModel($bookId);
       
       	// store a default json response as desired by editable
       	$out = Json::encode(['output'=>'', 'message'=>'']);
       
       	// fetch the first entry in posted data (there should
       	// only be one entry anyway in this array for an
       	// editable submission)
       	// - $posted is the posted data for Book without any indexes
       	// - $post is the converted array for single model validation
       	$post = [];
       	$posted = current($_POST['Product']);
       	$post['Product'] = $posted;
       
       	// load model like any single model validation
       	if ($model->load($post)) {
       		// can save model or do something before saving model
       		$model->save();
       
       		// custom output to return to be displayed as the editable grid cell
       		// data. Normally this is empty - whereby whatever value is edited by
       		// in the input by user is updated automatically.
       		$output = '';
       
       		// specific use case where you need to validate a specific
       		// editable column posted when you have more than one
       		// EditableColumn in the grid view. We evaluate here a
       		// check to see if buy_amount was posted for the Book model
       		if (isset($posted['price'])) {
       			$output =  Yii::$app->formatter->asDecimal($model->price, 2);
       		}
       
       		// similarly you can check if the name attribute was posted as well
       		if (isset($posted['name'])) {
       			$output =  $model->name;
       		}
       			$out = Json::encode(['output'=>$output, 'message'=>'']);
       	}
       	// return ajax json encoded response and exit
       	echo $out;
       	return;
       }
       
        return $this->render('index', [
        	'searchModel' => $searchModel,
        	'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionCreate()
    {
    	$model = new Product;
        $model->active = true;
    	$request = $this->request;
    	$client = $this->client;
    	$model->business_id = $client->id;
        $categories = $model->findAvailableCategories($client->id);

    	if($model->load($request->post()) && $model->validate()) {
    		if($model->save()) {
    			return $this->redirect(['index']);
    		}
    	}

    	if(empty($categories)) {
    	    $this->session->setFlash('error', 'No catalog categories available. Please create a category');

    	    return $this->redirect(['index']);
        }

    	return $this->render('create', [
    		'model' => $model,
    		'category' => $categories,
    	]);
    }
    
    public function actionView($id)
    {
      return $this->render('view', [
            'model' => $this->findModel($id),
        ]);	
    }
    
    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
    	$request = $this->request;
    	$session = $this->session;
    	
    	if($model->load($request->post()) && $model->validate()) {
    		if($model->save()) {
    			$session->setFlash('success', 'Product saved successfully');
    		} else {
    			$session->setFlash('error', 'Server error encountered while saving product');
    		}
    		return $this->redirect('index');
    	}
    	
    	return $this->render('update', [
    		'model' => $model,
    		'category' => $model->findAvailableCategories($this->client->id),
    		'category_id' => Category::findProductCategory($id)
    	]);
    }
    
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	$session = $this->session;
    	
    	if($model->delete()) {
    		$session->setFlash('success', 'Product deleted successfully');
    	} else {
    		$session->setFlash('error', 'Server error encountered while deleting product');
    	}
    	return $this->redirect('index');
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
