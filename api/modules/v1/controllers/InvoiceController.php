<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/13/15
 * Time: 7:17 PM
 */

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\User;
use api\modules\v1\models\Invoice;
use api\modules\v1\models\Billsource;

class InvoiceController extends AbstractBaseController
{
    public $modelClass = 'api\modules\v1\models\Invoice';

    public function actionFetchDebtors()
    {
        $get = $this->request->get();
        $user = User::findIdentityByAccessToken($get['token']);
        if(empty($user)) {
            return [
                'message' => 'Invalid request performed',
            ];
        }

        return (new Invoice())->fetchAllDebtors($user->id);
    }

    public function actionFetchCreditors()
    {
        $get = $this->request->get();
        $user = User::findIdentityByAccessToken($get['token']);
        if(empty($user)) {
            return [
                'message' => 'Invalid request performed',
            ];
        }

        return (new Invoice())->fetchAllCreditorInvoices($user);
    }

    public function actionFetchInvoice() 
    {
        $get = $this->request->get();
        $user = User::findIdentityByAccessToken($get['token']);
        if(empty($user)) {
            return [
                'message' => 'Invalid request performed',
            ];
        }

        return (new Billsource())->loadInvoice($get['id']);
    }
}