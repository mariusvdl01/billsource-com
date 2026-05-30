<?php
/**
 * Created by PhpStorm.
 * User: kenny
 * Date: 12/23/16
 * Time: 2:14 PM
 */

namespace common\models\sms;

use common\models\document\AbstractDocument as Bill;
use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\console\Exception;
use yii\web\ServerErrorHttpException;

class SmsManager extends Model
{
    private static $message = "Hi %s, %s has sent you a(n) %s for R%.2f. Visit %s to view %s. Contact biller on %s or %s.";

    protected $smsGateway;

    public static function queueNewBillNotification(Event $event)
    {
        $bill = $event->sender;
        $biller = $event->biller;

        $billDesc = 'Invoice';
        $mobile = $bill->client_mobile;
        $business_id = $biller->id;
        $email = Yii::$app->params['salesEmail'];
        $phone = Yii::$app->params['contactTel'];
        $server = Yii::$app->params['domain'];

        switch ($bill->type) {
            case Bill::TYPE_QUOTE :
                $billDesc = 'Quote';
                break;
            case Bill::TYPE_PAYSLIP :
                $billDesc = 'Payslip';
                break;
            case Bill::TYPE_TICKET :
                $billDesc = 'Ticket';
                break;
            case Bill::TYPE_UTILITY_BILL :
                $billDesc = 'Utility Bill';
                break;
            case Bill::TYPE_CASH_INVOICE :
                $billDesc = 'Cash invoice';
                break;
        }

        $debtor = isset($bill->alt_business_name) ? $bill->alt_business_name : 'there';
        if (isset($biller->email, $biller->phone_number)) {
            $email = $biller->email;
            $phone = $biller->phone_number;
        }

        $message = sprintf(self::$message, $debtor, $biller->trading_name, $billDesc, $bill->amount, $server, $billDesc, $phone, $email);

        $now = date('Y-m-d H:i:s', time());
        $model = new SmsHistory();
        $model->sms_uuid = self::getGUID();
        $model->sms_number = self::fixCellNumber($mobile);
        $model->sms_messages = $message;
        $model->sms_accepted_time = $now;
        $model->business_id = $business_id;
        $model->save(false);
    }

    /**
     * Provides a random globally unique identifier to tag sms storage in the system
     *
     * @return string $uuid the universal unique identifier
     */
    protected static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);                //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);                                    // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);

            return $uuid;
        }
    }

    /**
     * The method purpose is to format cellphone numbers properly before sending the actual sms
     * Add country code prefix and formats cell number.
     *
     * @return string $newno the fixed number.
     */
    protected static function fixCellNumber($no)
    {
        $newno = '27';
        $i = 0;
        $fn = 0;
        while ($i < strlen($no)) {
            if ($fn == 0 && $no[$i] == '+') {
                $i += 3;
                $newno .= '';
            } else if ($fn == 0 && $no[$i] == '0')
                $i += 1;
            else if (!is_numeric($no[$i]))
                $i += 1;
            else {
                if ($fn == 0)
                    $fn = 1;
                $newno .= $no[$i];
                $i += 1;
            }
        }
        if (strlen($newno) == 11)
            return $newno;
        else
            return '';
    }

    public function init()
    {
        parent::init();

        $this->smsGateway = Yii::$app->getModule('sms');
    }

    public function processSmsQueue(SmsHistory $smsHistory)
    {
        try {
            if (SmsHistory::canSendSMS($smsHistory->business_id)) {
                if ($this->smsGateway->sendSms(
                    $smsHistory->sms_number,
                    $smsHistory->sms_messages,
                    $smsHistory->sms_uuid
                )) {
                    $now = date('Y-m-d H:i:s', time());
                    $smsHistory->refresh();
                    $smsHistory->sms_delivered_time = $now;
                    $smsHistory->sms_send_time = $now;
                    $smsHistory->save();
                    $this->updateSmsCounter($smsHistory);
                    return true;
                }
            }
        } catch (\Exception $e) {
            throw new ServerErrorHttpException($e->getMessage(), 500);
        }
        return false;
    }

    protected function updateSmsCounter(SmsHistory $history)
    {
        return SmsLog::replaceCount($history->sms_number, $history->business_id);
    }

    public function sendSms($cellNumber, $message)
    {
        $mobileNumber = self::fixCellNumber($cellNumber);
        $transactionId = self::getGUID();

        try {
            $this->smsGateway->sendSms($mobileNumber, $message, $transactionId);
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Server error encountered while sending SMS', 500);
        }
    }
}
