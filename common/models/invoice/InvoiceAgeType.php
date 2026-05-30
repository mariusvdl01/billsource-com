<?php

namespace common\models\invoice;

use Yii;

/**
 * This is the model class for table "{{%invoice_age_type}}".
 *
 * @property integer $invoice_age_type_id
 * @property integer $minimum_days
 * @property integer $maximum_days
 * @property string $description
 * @property string $image
 * @property double $invoice_fee
 * @property double $business_fee
 * @property string $invoice_reference
 * @property string $debtor_description
 * @property string $creditor_description
 * @property string $invoice_description
 * @property integer $age_paid
 * @property integer $allow_payment
 */
class InvoiceAgeType extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice_age_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minimum_days', 'maximum_days', 'description'], 'required'],
            [['minimum_days', 'maximum_days', 'age_paid', 'allow_payment'], 'integer'],
            [['invoice_fee', 'business_fee'], 'number'],
            [['description', 'debtor_description', 'creditor_description', 'invoice_description'], 'string', 'max' => 300],
            [['image'], 'string', 'max' => 50],
            [['invoice_reference'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_age_type_id' => Yii::t('app', 'Invoice Age Type ID'),
            'minimum_days' => Yii::t('app', 'Minimum Days'),
            'maximum_days' => Yii::t('app', 'Maximum Days'),
            'description' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'invoice_fee' => Yii::t('app', 'Invoice Fee'),
            'business_fee' => Yii::t('app', 'Business Fee'),
            'invoice_reference' => Yii::t('app', 'Invoice Reference'),
            'debtor_description' => Yii::t('app', 'Debtor Description'),
            'creditor_description' => Yii::t('app', 'Creditor Description'),
            'invoice_description' => Yii::t('app', 'Invoice Description'),
            'age_paid' => Yii::t('app', 'Age Paid'),
            'allow_payment' => Yii::t('app', 'Allow Payment'),
        ];
    }

    /**
     * @inheritdoc
     * @return InvoiceAgeTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceAgeTypeQuery(get_called_class());
    }
}
