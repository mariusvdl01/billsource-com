<?php

namespace console\controllers;

use common\models\jobs\Notifier;
use yii\console\Controller;

class QueueController extends Controller
{
    /**
     * Process sms and email queues
     *
     */
    public function actionPerform()
    {
        $notifier = new Notifier;

        $notifier->processEmailQueue();
        $notifier->processSmsQueue();
    }
}
