<?php

namespace frontend\controllers\business\catalog;

use Yii;
use common\controllers\BusinessController;
use common\models\business\BusinessClient;

/**
 * Controller class to manage category entity as a tree set.
 * 
 * @author Kenneth Onah
 *
 */
class CategoryController extends BusinessController
{	
	
	/**
	 * TODO
	 */
    public function actionIndex()
    {    	
       
        return $this->render('index');
    }
    
    public function actionProduct()
    {
    	 
    	return $this->render('product');
    }
}
