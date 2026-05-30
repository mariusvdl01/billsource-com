<?php

namespace common\models\payment;

use Yii;
use DateTime;
use yii\behaviors\TimestampBehavior;
use common\models\Receipt;


/**
 * This is the model class for table "payment_fees".
 *
 * @property integer $payment_fees_id
 * @property integer $payment_index
 * @property string $reference
 * @property double $amount
 * @property integer $fee_paid
 * @property string $created_at
 *
 * @property Receipt[] $receipts
 */
class PaymentFees extends \common\models\BaseActiveRecord
{
	const TRANX_FEE = 7.50;
	const TRANX_FEE_REF = 'TRANSACTION FEE';
	const TRANX_FEE_UNPAID = 0;
	const TRANX_FEE_PAID = 1;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_fees';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_index', 'amount', 'fee_paid'], 'required'],
            [['payment_index', 'fee_paid'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['reference'], 'string', 'max' => 30]
        ];
    }

    public function behaviors()
    {
    	return [
    		[
    			'class' => TimestampBehavior::className(),
    			'updatedAtAttribute' => false,
    			'value' => function() {
    				$now = new DateTime();
    				return $now->format('Y-m-d H:i:s');
    			},
    		]
    	];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_fees_id' => Yii::t('app', 'Payment Fees ID'),
            'payment_index' => Yii::t('app', 'Payment Index'),
            'reference' => Yii::t('app', 'Reference'),
            'amount' => Yii::t('app', 'Amount'),
            'fee_paid' => Yii::t('app', 'Fee Paid'),
            'create_at' => Yii::t('app', 'Create At'),
        ];
    }

    public function getReceipts()
    {
        return $this->hasOne(Receipt::className(), ['receipt_id' => 'payment_index']);
    }
}
