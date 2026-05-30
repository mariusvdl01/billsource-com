<?php

namespace common\models\sms;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sms_notify_history".
 *
 * @property string $sms_number
 * @property string $sms_date
 * @property string $created_at
 */
class SmsNotifyHistory extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_notify_history';
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
                'value'              => function () {
                    $now = new DateTime();

                    return $now->format('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sms_number', 'sms_date'], 'required'],
            [['created_at'], 'safe'],
            [['sms_number'], 'string', 'max' => 16],
            [['sms_date'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sms_number' => Yii::t('app', 'Sms Number'),
            'sms_date'   => Yii::t('app', 'Sms Date'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public static function hasSentSMS($num, $date)
    {
        $query = self::find();
        $result = $query->where(['sms_number' => $num])
            ->andWhere(['sms_date' => $date])->all();

        return $result === false ? null : $result;
    }

    function logSmsNotification($num, $date)
    {
        $sms = new self;
        $sms->sms_number = $num;
        $sms->sms_date = $date;

        return $sms->save();
    }
}
