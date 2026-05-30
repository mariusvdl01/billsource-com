<?php

namespace common\models\invoice;

use common\models\catalog\Product;
use common\traits\ActiveRecordTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "invoice_line".
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $product_id
 * @property string $line_description
 * @property double $line_amount
 * @property double $line_qty
 * @property double $line_unit_price
 * @property integer $line_progress_value
 * @property integer $line_progress_maximum
 * @property Invoice $invoice
 */
class InvoiceLine extends ActiveRecord
{
    use ActiveRecordTrait;

    /**
     * InvoiceLine constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->line_amount = 0;
        $this->line_unit_price = 0;
        $this->line_qty = 1;
    }

    /**
	 * @inheritdoc
	 */
	public static function tableName()
    {
		return '{{%invoice_line}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
    {
		return [
			[['invoice_id', 'line_description', 'line_amount', 'line_qty', 'line_unit_price'], 'required', 'message' => 'Field required'],
			[['invoice_id', 'line_progress_value', 'line_progress_maximum'], 'integer'],
			[['line_amount', 'line_qty', 'line_unit_price'], 'number', 'message' => 'Invalid value'],
			[['line_description'], 'string'],
            [['line_amount', 'line_unit_price'], 'default', 'value' => 0],
            ['line_qty', 'default', 'value' => 1]
		];
	}

	/**
	 *@inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => false,
				'updatedAtAttribute' => false,
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
    {
		return [
            'id' => Yii::t ( 'app', 'Invoice Line ID' ),
            'invoice_id' => Yii::t ( 'app', 'Invoice ID' ),
            'product_id' => Yii::t ( 'app', 'Product ID' ),
            'line_description' => Yii::t ( 'app', 'Line Description' ),
            'line_amount' => Yii::t ( 'app', 'Line Amount' ),
            'line_qty' => Yii::t ( 'app', 'Line Qty' ),
            'line_unit_price' => Yii::t ( 'app', 'Line Unit Price' ),
            'line_progress_value' => Yii::t ( 'app', 'Line Progress Value' ),
            'line_progress_maximum' => Yii::t ( 'app', 'Line Progress Maximum' )
		];
	}

	/**
	 *
	 * @return ActiveQuery
	 */
	public function getInvoice()
    {
		return $this->hasOne (Invoice::class, ['id' => 'invoice_id']);
	}

    /**
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne (Product::class, ['id' => 'product_id']);
    }

    /**
     * @param $model
     * @param $itemsId
     * @throws \yii\db\Exception
     */
	public static function deleteOldInvoiceLines($model, $itemsId)
	{
		$query = self::findBySql(
			"DELETE FROM " . self::tableName()
			. " WHERE id NOT IN (" . implode(',', $itemsId)
			. ") AND invoice_id = " . $model->primaryKey
		);
		$query->createCommand()->execute();
	}
}
