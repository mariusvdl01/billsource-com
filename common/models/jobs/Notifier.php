<?php

namespace common\models\jobs;


use common\helpers\Billsource;
use common\models\email\MailLog;
use common\models\email\MailNotificationLog;
use common\models\sms\SmsHistory;
use common\models\sms\SmsNotifyHistory;
use Yii;
use yii\base\Model;

class Notifier extends Model 
{
    protected $smsManager;
    protected $mailManager;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->smsManager = Yii::$app->smsManager;
        $this->mailManager = Yii::$app->mailManager;
    }

    /**
     * Sends SMS remainder
     *
     * @see 'console\config\schedule' For cron task details
     */
    public function sendReminderSms()
    {
        if (($sms = $this->findRemainderSms()) !== false)
    	{
    		foreach($sms as $row)
    		{
                $mobile = $row['mobile'];
                $message = $this->buildSmsMessage($row['debtor']);

                if ($mobile == '')
                    $mobile = 'Invalid cellphone number';

                $send = ' not sent';
                if (SmsNotifyHistory::hasSentSMS($mobile, $row['date']))
                    $send = ' already sent';
                else if ($mobile == 'Invalid cellphone number')
                    $send = " won't send sms";
                else if ($this->smsManager->sendSms($mobile, $message)) {
                    (new SmsNotifyHistory)->logSmsNotification($mobile, $row['date']);
                    $send = ' sent';
                }

                if ('dev' == YII_ENV)
                    Yii::info('Send SMS \'' . $message . '\' orig no ' . $row['mobile'] . ' fixcell ' . $mobile . ' on ' . $row['date'] . $send . "\n");
            }
        }
        if ('dev' == YII_ENV)
            Yii::info('Processed ' . count($sms) . ' number of sms\'s' . "\n");
    }

    /**
     * SMS remainders
     *
     * @return array
     */
    protected function findRemainderSms()
    {
        return Billsource::findSmsToSend();
    }

    /**
     * Sms message
     *
     * @param string $debtor
     *
     * @return string
     */
    public function buildSmsMessage($debtor = 'Debtor')
    {
        $message = "Good day $debtor, Billsource has outstanding bills for you from one or more suppliers. Register or login at billsource.com to view or pay them. Contact support@billsource.com or (011) 027 4123 for more information.";
        return trim($message);
    }

    /**
     * Sends Email remainder
     *
     * @see 'console\config\schedule' For cron task details
     */
    public function sendReminderEmail()
    {
        if (($emails = $this->findRemainderEmail()) !== false)
    	{
            foreach ($emails as $email)
    		{
    			$send = ' not send';
    			$config = [
                    'email' => $email['email'],
    				'subject' => 'Outstanding Bills',
    				'template' => [
    					'html' => 'billNotification-html',
    					'text' => 'billNotification-text',
    				]
    			];
                if (MailNotificationLog::hasSentEmail($email['email'], $email['date'])) {
    				$send = ' send';
                } else if ($this->mailManager->sendEmail($config)) {
                    MailNotificationLog::logEmailNotification($email['email'], $email['date']);
    			}
                if ('dev' == YII_ENV)
                    Yii::info('Send email  ' . $email['email'] . ' on ' . $email['date'] . $send . "\n");
    		}
    	}
        if ('dev' == YII_ENV)
            Yii::info('Processed ' . count($emails) . ' number of email\'s' . "\n");
    }

    /**
     * Email remainders
     *
     * @return array
     */
    protected function findRemainderEmail()
    {
        return Billsource::findEmailToSend();
    }

    /**
     * Email queue processor. Method is called by a cron job
     *
     * @see 'console\config\schedule' For cron task details
     */
    public function processEmailQueue()
    {
        $result = null;
        $audit = Yii::$app->auditManager;

        if (($result = $audit->findCurrentEmailsSentCounter()) !== false)
        {
            $limit = $result['max_emails_hour'];
            $count = 0;
            if (isset($result['count']))
                $count = $result['count'];

            if($limit > $count)
            {
                $maxRows = $limit - $count;

                if (($mails = MailLog::findQueuedMails($maxRows)) !== false) {
                    if (is_array($mails)) {
                        foreach ($mails as $mail) {
                            if ($this->sendEmail($mail)) {
                                $mail->send_time = date_create('now')->format('Y-m-d H:i:s');
                                $mail->save();
                            }
                        }
                    }
                    if ('dev' == YII_ENV)
                        Yii::info('Resent ' . count($mails) . ' email\'s' . "\n");
                }
            }
        }
    }

    protected function sendEmail(MailLog $queue)
    {
        if (isset($queue->id)) {
            return $this->mailManager->processEmailQueue($queue);
        } else {
            return false;
        }
    }

    /**
     * SMS queue processor. Method is called by a cron job
     *
     * @see 'console\config\schedule' For cron task details
     */
    public function processSmsQueue()
    {
        if (($messages = SmsHistory::findQueuedMessages()) !== false) {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    $this->sendSms($message);
                }
            }
            if (YII_DEBUG)
                Yii::info('Resent ' . count($messages) . ' email\'s' . "\n");
        }
    }

    protected function sendSms(SmsHistory $message)
    {
        if (isset($message->id)) {
            return $this->smsManager->processSmsQueue($message);
        } else {
            return false;
        }
    }
}
