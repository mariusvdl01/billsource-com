<?php

namespace common\models\email;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%mail_count}}".
 *
 * @property string $key
 * @property integer $count
 * @property string $created_at
 * @property string $updated_at
 */
class MailCount extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_count}}';
    }

    public static function replaceCounter($mail)
    {
        $mailCount = null;
        if (isset($mail)) {
            if (!isset($mail['count'])) {
                $mailCount = new self;
                $mailCount->key = $mail['key'];
                $mailCount->count = 1;

            } else {
                $mailCount = self::findOne(['key' => $mail['key']]);
                $mailCount->count = intval($mail['count']) + 1;
            }

            return $mailCount->save();
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'count'], 'required'],
            [['count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['key'], 'string', 'max' => 32],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => function () {
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
            'key'        => Yii::t('app', 'Key'),
            'count'      => Yii::t('app', 'Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
