<?php

namespace common\models;

use common\traits\ActiveRecordTrait;
use Yii;
use common\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%transaction_log}}".
 */
class TransactionLog extends ActiveRecord
{
    use ActiveRecordTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'profile_id'], 'required'],
            [['reference', 'status', 'response'], 'string']
        ];
    }

        public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false, // disable updated_at
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }


    // /**
    //  * @inheritdoc
    //  */
    // public function attributeLabels()
    // {
    //     return [
    //         'id'  => Yii::t('app', 'Reading ID'),
    //         'image'       => Yii::t('app', 'Image'),
    //         'description' => Yii::t('app', 'Description'),
    //     ];
    // }
}
