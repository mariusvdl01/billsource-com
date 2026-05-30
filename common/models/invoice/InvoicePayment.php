<?php

namespace common\models\invoice;

use common\models\individual\IndividualClient;
use common\models\payment\PaymentFees;
use common\models\Receipt;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "invoice_payment".
 *
 * @property integer $id
 * @property string $pay_index
 * @property integer $invoice_id
 * @property string $payment_reference
 * @property double $payment_amount
 * @property integer $payment_result
 * @property integer $user_id
 * @property string $date
 *
 * @property Invoice $invoice
 * @property Receipt $receipt
 * @property PaymentFees[] $paymentFees
 * @property IndividualClient $individual
 */
class InvoicePayment extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice_payment}}';
    }

    public function behaviors()
    {
    	return [
    		[
    			'class' => TimestampBehavior::className(),
    			'createdAtAttribute' => 'date',
    			'updatedAtAttribute' => false,
                'value' => function() {
                    $now = new \DateTime();
                    return $now->format('Y-m-d');
                }
    		]
    	];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_index', 'invoice_id', 'payment_reference', 'payment_amount', 'user_id'], 'required'],
            [['pay_index', 'invoice_id', 'payment_result', 'user_id'], 'integer'],
        	['payment_reference', 'string'],
            ['date', 'safe'],
            [['payment_amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'    => Yii::t('app', 'Invoice Payment ID'),
            'pay_index'             => Yii::t('app', 'Pay Index'),
            'invoice_id'            => Yii::t('app', 'Invoice ID'),
            'payment_reference'     => Yii::t('app', 'Payment Reference'),
            'payment_amount'        => Yii::t('app', 'Payment Amount'),
            'payment_result'        => Yii::t('app', 'Payment Result'),
        	'user_id'		        => Yii::t('app', 'User ID'),
            'date'                  => Yii::t('app', 'Date of Payment'),
        ];
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
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    public function getData($id) {
        $query = self::find();
        $result = $query->where('[[invoice_id]]=:id', [':id' => $id])
            ->andWhere('[[payment_result]]=:result', [':result' => 0])
            ->one();

        return $result;
    }

    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['id' => 'pay_index']);
    }

    public function getPaymentFees()
    {
        return $this->hasMany(PaymentFees::className(), ['payment_index' => 'pay_index']);
    }
}
