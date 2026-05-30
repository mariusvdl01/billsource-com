<?php

namespace common\models\collector;

use Yii;

/**
 * This is the model class for table "{{%collectors_debtors}}".
 *
 * @property integer $id
 * @property integer $status_id
 * @property integer $collector_id
 * @property string $alt_business_name
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
 * @property integer $paid
 * @property string $comments
 * @property string $marketing
 * @property integer $creditor
 * @property double $subtotal
 * @property double $vat
 * @property double $total
 */
class CollectorsDebtors extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collectors_debtors}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'collector_id', 'paid', 'creditor'], 'integer'],
            [['collector_id', 'client_email', 'reference_number', 'amount', 'paid', 'comments'], 'required'],
            [['deleted'], 'boolean'],
            [['issue_date', 'due_date'], 'safe'],
            [['discount', 'amount', 'subtotal', 'vat', 'total'], 'number'],
            [['alt_business_name'], 'string', 'max' => 255],
            [['client_id', 'client_mobile'], 'string', 'max' => 30],
            [['client_email'], 'string', 'max' => 100],
            [['client_vat'], 'string', 'max' => 50],
            [['reference_number'], 'string', 'max' => 128],
            [['comments', 'marketing'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'collector_id' => Yii::t('app', 'Collector ID'),
            'alt_business_name' => Yii::t('app', 'Alt Business Name'),
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
            'paid' => Yii::t('app', 'Paid'),
            'comments' => Yii::t('app', 'Comments'),
            'marketing' => Yii::t('app', 'Marketing'),
            'creditor' => Yii::t('app', 'Creditor'),
            'subtotal' => Yii::t('app', 'Subtotal'),
            'vat' => Yii::t('app', 'Vat'),
            'total' => Yii::t('app', 'Total'),
        ];
    }

    /**
     * @inheritdoc
     * @return CollectorsBinQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CollectorsBinQuery(get_called_class());
    }
}
