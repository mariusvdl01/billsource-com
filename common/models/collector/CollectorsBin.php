<?php

namespace common\models\collector;

use common\models\invoice\Invoice;
use Yii;

/**
 * This is the model class for table "{{%collectors_bin}}".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $collector_id
 * @property integer $paid
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Invoice $invoice
 */
class CollectorsBin extends \common\models\BaseActiveRecord
{
    const BATCH_COUNT = 100;

    protected static $columns = array(
        'invoice_id', 'paid',
        'created_at', 'updated_at'
    );
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collectors_bin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'collector_id', 'paid'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' =>
                ['invoice_id' => 'invoice_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Bin ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
            'collector_id' => Yii::t('app', 'Collector ID'),
            'paid' => Yii::t('app', 'Paid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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

    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
    
    public static function getInsertColumns() {
        return self::$columns;
    }
}
