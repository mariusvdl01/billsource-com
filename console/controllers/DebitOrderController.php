<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 4/23/16
 * Time: 5:04 PM
 */

namespace console\controllers;

use common\models\DebitOrder;
use yii\console\Controller;

class DebitOrderController extends Controller
{
    /**
     * Run the debit orders periodically
     *
     */
    public function actionRun()
    {
        DebitOrder::updateDebitOrdersList();
    }
}