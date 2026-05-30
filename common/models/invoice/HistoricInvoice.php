<?php

namespace common\models\invoice;

use Yii;

/**
 * This is the model class for table "historic_invoice".
 *
 * @property integer $historic_invoice_id
 * @property integer $invoice_id
 * @property integer $business_id
 * @property boolean $deleted
 * @property string $client_id
 * @property string $client_email
 * @property string $client_mobile
 * @property string $client_vat
 * @property string $reference_number
 * @property string $issue_date
 * @property string $due_date
 * @property double $discount
 * @property double $amount
 * @property integer $is_invoice_paid
 * @property string $comments
 * @property string $marketing
 * @property integer $creditor
 * @property double $vat
 * @property double $total
 *
 * @property BusinessClient $business
 * @property Invoice $invoice
 */
class HistoricInvoice extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'historic_invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'business_id', 'client_id', 'client_email', 'reference_number', 'issue_date', 'due_date', 'amount', 'is_invoice_paid', 'comments'], 'required'],
            [['invoice_id', 'business_id', 'is_invoice_paid', 'creditor'], 'integer'],
            [['deleted'], 'boolean'],
            [['issue_date', 'due_date'], 'safe'],
            [['discount', 'amount', 'vat', 'total'], 'number'],
            [['client_id', 'client_email', 'client_mobile'], 'string', 'max' => 255],
            [['client_vat'], 'string', 'max' => 50],
            [['reference_number'], 'string', 'max' => 30],
            [['comments', 'marketing'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'historic_invoice_id' => Yii::t('app', 'Historic Invoice ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'deleted' => Yii::t('app', 'Deleted'),
            'client_id' => Yii::t('app', 'Client ID'),
            'client_email' => Yii::t('app', 'Client Email'),
            'client_mobile' => Yii::t('app', 'Client Mobile'),
            'client_vat' => Yii::t('app', 'Client Vat'),
            'reference_number' => Yii::t('app', 'Reference Number'),
            'issue_date' => Yii::t('app', 'Issue Date'),
            'due_date' => Yii::t('app', 'Due Date'),
            'discount' => Yii::t('app', 'Discount'),
            'amount' => Yii::t('app', 'Amount'),
            'is_invoice_paid' => Yii::t('app', 'Is Invoice Paid'),
            'comments' => Yii::t('app', 'Comments'),
            'marketing' => Yii::t('app', 'Marketing'),
            'creditor' => Yii::t('app', 'Creditor'),
            'vat' => Yii::t('app', 'Vat'),
            'total' => Yii::t('app', 'Total'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessClient::className(), ['business_id' => 'business_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['invoice_id' => 'invoice_id']);
    }
}
