<?php

namespace common\models;

use common\models\payment\PaymentFees;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "receipt".
 *
 * @property integer $id
 * @property boolean $paid
 * @property string $response_time
 * @property string $response_3d_status
 * @property string $response_error_code
 * @property string $response_error_details
 * @property string $response_bank_error_code
 * @property string $response_bank_error_details
 * @property string $response_result
 * @property string $response_bank_error_message
 * @property string $response_error_source
 *
 * @property PaymentFees[] $paymentFees
 */
class Receipt extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%receipt}}';
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
                'createdAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paid'], 'boolean'],
            [['response_time'], 'safe'],
            [['response_3d_status'], 'string', 'max' => 10],
            [['response_error_code'], 'string', 'max' => 30],
            [['response_error_details'], 'string', 'max' => 255],
            [['response_bank_error_code'], 'string', 'max' => 128],
            [['response_bank_error_details', 'response_bank_error_message'], 'string', 'max' => 1024],
            [['response_result', 'response_error_source'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                          => Yii::t('app', 'Receipt ID'),
            'paid'                        => Yii::t('app', 'Paid'),
            'response_time'               => Yii::t('app', 'Response Time'),
            'response_3d_status'          => Yii::t('app', 'Response 3d Status'),
            'response_error_code'         => Yii::t('app', 'Response Error Code'),
            'response_error_details'      => Yii::t('app', 'Response Error Details'),
            'response_bank_error_code'    => Yii::t('app', 'Response Bank Error Code'),
            'response_bank_error_details' => Yii::t('app', 'Response Bank Error Details'),
            'response_result'             => Yii::t('app', 'Response Result'),
            'response_bank_error_message' => Yii::t('app', 'Response Bank Error Message'),
            'response_error_source'       => Yii::t('app', 'Response Error Source'),
        ];
    }

    public function getPaymentFees()
    {
        return $this->hasMany(PaymentFees::className(), ['payment_index' => 'id']);
    }
}
