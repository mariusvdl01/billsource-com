<?php

namespace common\models\email;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%mail_log}}".
 *
 * @property integer $id
 * @property string $headers
 * @property string $recipients
 * @property string $subject
 * @property string $message
 * @property string $send_time
 * @property string $created_at
 */
class MailLog extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_log}}';
    }

    public static function findQueuedMails($limit = 100)
    {
        $mails = self::find()
            ->where(['send_time' => null])
            ->andWhere(['not', ['recipients' => null]])
            ->andWhere(['not', ['subject' => null]])
            ->andWhere(['not', ['message' => null]])
            ->andWhere(['not', ['headers' => null]])
            ->limit($limit)
            ->all();


        return $mails;
    }

    public static function findQueueById($id)
    {
        return self::find()
            ->where('[[id]]=:id', [':id' => $id])
            ->one();
    }

    public static function saveMail(MailLog $mail)
    {
        if (isset($mail->recipients) && $mail->recipients) {
            if ($mail->save()) {
                $mail->refresh();
                return $mail->getPrimaryKey();
            }
        }

        return false;
    }

    public static function updateMailLog($queue)
    {
        $mail = self::find()->where('[[id]]=:id', [':id' => $queue->id])->one();
        if ($mail) {
            $now = new DateTime;
            $mail->send_time = $now->format('Y-m-d H:i:s');
            return $mail->save();
        }

        return false;
    }

    public static function queueEmail($queue)
    {
        $msg = $subj = $head = null;

        if (isset($queue->message) && strlen($queue->message) != 0)
            $msg = $queue->message;
        if (isset($queue->subject) && strlen($queue->subject) != 0)
            $subj = $queue->subject;
        if (isset($queue->head) && strlen($queue->head) != 0)
            $head = $queue->head;

        $mailLog = self::find()
            ->where('send_time IS NULL AND id =' . $queue->id)
            ->one();
        if ($mailLog) {
            $mailLog->message = $msg;
            $mailLog->subject = $subj;
            $mailLog->headers = $head;

            return $mailLog->save();
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headers', 'message'], 'string'],
            [['recipients'], 'required'],
            [['send_time', 'created_at'], 'safe'],
            [['recipients', 'subject'], 'string', 'max' => 512],
        ];
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
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'Mail Log ID'),
            'headers'     => Yii::t('app', 'Headers'),
            'recipients'  => Yii::t('app', 'Recipients'),
            'subject'     => Yii::t('app', 'Subject'),
            'message'     => Yii::t('app', 'Message'),
            'send_time'   => Yii::t('app', 'Send Time'),
            'created_at'  => Yii::t('app', 'Created At'),
        ];
    }
}
