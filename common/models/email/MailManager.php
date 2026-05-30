<?php

namespace common\models\email;

use common\models\business\BusinessClient;
use common\models\invoice\Invoice as Bill;

use Yii;
use yii\base\Event;
use yii\base\Model;

class MailManager extends Model
{
    protected $mailer;
    protected $auditManager;

    public static function queueNewBillNotification(Event $event)
    {
        $bill = $event->sender;
        $biller = $event->biller;

        $headers = array(
            'bill_id' => $bill->id,
            'biller_id' => $biller->id,
        );

        $messageTemplate = array(
            'html' => 'customerBillNotification-html',
            'text' => 'customerBillNotification-text'
        );

        $mailQueue = new MailLog();
        $mailQueue->headers = serialize($headers);
        $mailQueue->message = serialize($messageTemplate);
        $mailQueue->recipients = serialize(array($bill->client_email));
        $mailQueue->subject = 'Billsource - ' . $bill->getTypeDescription()[$bill->type] . ' Notification';
        $mailQueue->save(false);
    }

    public function init()
    {
        parent::init();

        $this->mailer = Yii::$app->mailer;
        $this->auditManager = Yii::$app->auditManager;
    }

    public function processEmailQueue($queue)
    {
        $mailCount = null;
        $audit = $this->auditManager;

        if (!isset($queue->id)
            || ($queue->id = MailLog::saveMail($queue)) !== false
        ) {
            $config = $this->getEmailParams($queue);
            if ($config && $this->sendEmail($config))
                return $audit->updateMailQueue($queue) && $audit->updateMailCount($mailCount);
            else
                return $audit->queueEmail($queue);

        } else {
            return false;
        }
    }

    protected function getEmailParams($queue)
    {
        $headers = unserialize($queue->headers);
        $bill = Bill::findOne($headers['bill_id']);
        $biller = BusinessClient::findOne($headers['biller_id']);

        if(!isset($bill, $biller))
            return false;

        $templateParams = array(
            'biller' => BusinessClient::findOne($headers['biller_id']),
            'bill' => Bill::findOne($headers['bill_id'])
        );

        $config = [
            'mail_id' => $queue->id,
            'email' => unserialize($queue->recipients),
            'subject' => $queue->subject,
            'template' => unserialize($queue->message),
            'params' => $templateParams,
        ];

        return $config;
    }

    public function sendEmail($config)
    {
        $to = $config['email'];
        $subject = $config['subject'];
        $params = isset($config['params']) ? $config['params'] : [];
        $template = isset($config['template']) ? $config['template'] : [];

        return $this->mailer->sendEmailWithTemplate($to, $subject, $template, $params, Yii::$app);
    }
}
