<?php

namespace common\models;

use common\helpers\ArrayHelper;
use common\models\business\BusinessClient;
use common\models\email\MailCount;
use common\models\email\MailLog;
use common\models\individual\IndividualClient;
use common\models\payment\Response;
use common\models\sms\SmsHistory;
use DateTime;

/**
 * This is the model class for table "audit_trail".
 *
 * Audit logging
 * @property integer $id
 * @property integer $user_id
 * @property string $audit_form
 * @property string $audit_action
 * @property string $audit_memo
 * @property string $ip_addr
 * @property string $created_at
 *
 * @property User $user
 * @property BusinessClient $businessClient
 * @property IndividualClient $individualClient
 */
class AuditTrail extends BaseActiveRecord
{
    /**
     * Provides the name of the table
     *
     * @return string the name of the table
     */
    public static function tableName()
    {
        return '{{%audit_trail}}';
    }

    /**
     * Overrides the default behavior inherited from parent class
     *
     * @override
     */
    public function behaviors()
    {
        return [
            [
                'class'              => \yii\behaviors\TimestampBehavior::className(),
                'updatedAtAttribute' => false,
                'value'              => function () {
                    $now = new DateTime();

                    return $now->format('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array an array of validation rules
     */
    public function rules()
    {
        return [
            [['user_id', 'audit_form', 'audit_action', 'audit_memo'], 'required'],
            [['user_id'], 'integer'],
            [['audit_form', 'audit_memo', 'audit_action', 'ip_addr'], 'string'],
            [['audit_form', 'audit_action', 'ip_addr'], 'string', 'max' => 255],
            ['created_at', 'safe'],
        ];
    }

    /**
     * Saves log information. This method is not meant to capture system or server logs.
     *
     * @return boolean true | false
     */
    public function log($id, $form, $action, $message, $ip_addr = null)
    {
        $this->user_id = intval($id);
        $this->audit_form = $form;
        $this->audit_action = $action;
        $this->audit_memo = $message;
        $this->ip_addr = $ip_addr;

        return $this->save();
    }

    /**
     * Saves response details from payment gateway
     *
     * @return boolean true | false
     */
    public function storePaymentResult($scriptFile/*, $request*/)
    {
        $response = new Response();
        $response->file = $scriptFile;
        $response->save();

        /*
        if (!empty($request->post())) {
            $pk = $response->getPrimaryKey();
            $responseDetail = new ResponseDetail();
            $responseDetail->response_id = $pk;
            $responseDetail->type = $request->method;
            $responseDetail->data = serialize($request->post());
            $responseDetail->save();
        }
        */
        return true;
    }

    public function findCurrentEmailsSentCounter()
    {
        $sql = 'SELECT `id`, `max_emails_hour`,
   				IFNULL(`key`, DATE_FORMAT(NOW(), \'%Y%m%d%H\')) `key`, `count` 
   				FROM `company` 
   				LEFT JOIN (SELECT * FROM `mail_count` 
   							WHERE `key` = DATE_FORMAT(NOW(), \'%Y%m%d%H\')) `b` 
   				ON 1 = 1';
        $result = self::findBySql($sql)->createCommand()->queryAll();
        $row = [];
        if ($result) {
            ArrayHelper::recursive($result, $row);
            return $row;
        }

        return false;
    }

    public function findCurrentSmsSentCounter($business_id)
    {
        SmsHistory::canSendSMS($business_id);
    }

    /**
     * Update mail
     *
     * @param $id
     *
     * @return bool
     */
    public function updateMailQueue(MailLog $queue)
    {
        return MailLog::updateMailLog($queue);
    }

    /**
     * Update the counter for mails sent out
     *
     * @param $mail
     *
     * @return bool
     */
    public function updateMailCount($mail)
    {
        return MailCount::replaceCounter($mail);
    }

    public function queueEmail($queue)
    {
        return MailLog::queueEmail($queue);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessClient()
    {
        return $this->hasOne(BusinessClient::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividualClient()
    {
        return $this->hasOne(IndividualClient::className(), ['user_id' => 'user_id']);
    }
}
