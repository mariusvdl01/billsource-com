<?php

namespace common\models\email;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%mail_notification_log}}".
 *
 * @property string $notify_email
 * @property string $notify_month
 * @property string $created_at
 */
class MailNotificationLog extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_notification_log}}';
    }

    public static function hasSentEmail($email, $date)
    {
        $query = self::find();
        $result = $query->where(['notify_email' => $email])
            ->andWhere(['notify_month' => $date])->all();

        return $result === false ? false : $result;
    }

    public static function logEmailNotification($email, $date)
    {
        $mailNotifyLog = new self;
        $mailNotifyLog->notify_email = $email;
        $mailNotifyLog->notify_month = $date;

        return $mailNotifyLog->save();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notify_email', 'notify_month'], 'required'],
            [['notify_month', 'created_at'], 'safe'],
            [['notify_email'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
                'value'              => function () {
                    $now = new DateTime;
                    return $now->format('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notify_email' => Yii::t('app', 'Notify Email'),
            'notify_month' => Yii::t('app', 'Notify Month'),
            'created_at'   => Yii::t('app', 'Created At'),
        ];
    }
}
