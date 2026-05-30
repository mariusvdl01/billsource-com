<?php

namespace common\models\sms;

use common\models\BaseActiveRecord;
use common\models\business\BusinessClient;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "sms_history".
 *
 * @property integer $sms_history_id
 * @property integer $business_id
 * @property string $sms_uuid
 * @property string $sms_number
 * @property string $sms_messages
 * @property string $sms_send_time
 * @property string $sms_accepted_time
 * @property string $sms_delivered_time
 *
 * @property BusinessClient $business
 */
class SmsHistory extends BaseActiveRecord
{
    /**
     * Provides the name of the table
     *
     * @return string $tableName the name of the table
     */
    public static function tableName()
    {
        return 'sms_history';
    }

    public static function findQueuedMessages($limit = 10)
    {
        $messages = self::find()
            ->where(['sms_send_time' => null])
            ->andWhere(['not', ['business_id' => null]])
            ->andWhere(['not', ['sms_uuid' => null]])
            ->andWhere(['not', ['sms_number' => null]])
            ->andWhere(['not', ['sms_messages' => null]])
            ->limit($limit)
            ->all();


        return $messages;
    }

    public static function canSendSMS($business_id)
    {
        $query = new Query();
        $data = $query->select('IFNULL(`b`.`maximum_limit_sms`, `p`.`maximum_limit_sms`) AS `max_sms`, `s`.`count`')
            ->from('`business_client` `b`')
            ->innerJoin('`business_profile` `p`', '`b`.`profile_id` = `p`.`id`')
            ->leftJoin("(SELECT * FROM `sms_log` WHERE `period` = DATE_FORMAT(CURRENT_DATE(), '%Y%m')) `s`",
                '`b`.`id` = `s`.`business_id`')
            ->where('[[b.id]]=:id', [':id' => $business_id])
            ->createCommand()->queryOne();

        if ($data === false)
            return true;

        if ($data['max_sms'] > $data['count']) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array of validation rules
     */
    public function rules()
    {
        return [
            [['business_id'], 'integer'],
            [['sms_uuid', 'sms_number', 'sms_messages'], 'required'],
            [['sms_send_time', 'sms_accepted_time', 'sms_delivered_time'], 'safe'],
            [['sms_uuid'], 'string', 'max' => 128],
            [['sms_number'], 'string', 'max' => 32],
            [['sms_messages'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sms_history_id'     => Yii::t('app', 'Sms History ID'),
            'business_id'        => Yii::t('app', 'Business ID'),
            'sms_uuid'           => Yii::t('app', 'Sms Uuid'),
            'sms_number'         => Yii::t('app', 'Sms Number'),
            'sms_messages'       => Yii::t('app', 'Sms Messages'),
            'sms_send_time'      => Yii::t('app', 'Sms Send Time'),
            'sms_accepted_time'  => Yii::t('app', 'Sms Accepted Time'),
            'sms_delivered_time' => Yii::t('app', 'Sms Delivered Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessClient::className(), ['business_id' => 'business_id']);
    }
}
