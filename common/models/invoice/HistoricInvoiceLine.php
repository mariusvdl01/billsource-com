<?php

namespace common\models\invoice;

use Yii;

/**
 * This is the model class for table "historic_invoice_line".
 *
 * @property integer $invoice_line_id
 * @property integer $invoice_id
 * @property string $invoice_line_description
 * @property double $invoice_line_amount
 * @property integer $invoice_line_qty
 * @property double $invoice_line_unit_price
 *
 * @property Invoice $invoice
 */
class HistoricInvoiceLine extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'historic_invoice_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'invoice_line_description', 'invoice_line_amount'], 'required'],
            [['invoice_id', 'invoice_line_qty'], 'integer'],
            [['invoice_line_amount', 'invoice_line_unit_price'], 'number'],
            [['invoice_line_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_line_id' => Yii::t('app', 'Invoice Line ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
            'invoice_line_description' => Yii::t('app', 'Invoice Line Description'),
            'invoice_line_amount' => Yii::t('app', 'Invoice Line Amount'),
            'invoice_line_qty' => Yii::t('app', 'Invoice Line Qty'),
            'invoice_line_unit_price' => Yii::t('app', 'Invoice Line Unit Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['invoice_id' => 'invoice_id']);
    }
}
