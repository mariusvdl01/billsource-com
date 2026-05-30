<?php

namespace console\controllers;

use common\models\jobs\Notifier;
use yii\console\Controller;

class PaymentReminderController extends Controller
{
    /**
     * Send out remainders for outstanding bills
     *
     */
    public function actionNotify()
    {
        $notifier = new Notifier;

        $notifier->sendReminderSms();
        $notifier->sendReminderEmail();
    }
}
