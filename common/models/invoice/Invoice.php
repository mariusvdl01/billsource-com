<?php

namespace common\models\invoice;

use common\helpers\ArrayHelper;
use common\models\Status;
use yii\base\Model;

class Invoice extends \common\models\document\AbstractInvoiceDocument
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->deleted = self::NOT_DELETED;
        $this->type = self::TYPE_INVOICE;
        $this->paid = self::INVOICE_UNPAID;
        $this->status_id = Status::findOne(['code' => Status::STATUS_SENT])->id;
    }

    /**
     * Handles creating a new invoice when a quote is accepted.
     *
     * @param Quote $quote
     */
    public static function createNewInvoice(Quote $quote)
    {
        $lineItems = [];

        $transaction = \Yii::$app->db->beginTransaction();

        $invoice = new Invoice();
        $invoice->setAttributes($quote->getAttributes());
        $invoice->read = 0;
        $invoice->deleted = self::NOT_DELETED;
        $invoice->type = self::TYPE_INVOICE;
        $invoice->paid = self::INVOICE_UNPAID;
        $invoice->status_id = Status::findOne(['code' => Status::STATUS_SENT])->id;

        $valid = $invoice->save();
        $invoice->refresh();

        $quoteLineManager = new InvoiceLineManager($quote);
        $items = $quoteLineManager->getItems();

        if($valid && $items) {
            foreach ($items as $item) {
                $lineItem = new InvoiceLine;
                $lineItem->invoice_id = $invoice->id;
                $lineItem->line_qty = $item->line_qty;
                $lineItem->line_description = $item->line_description;
                $lineItem->line_unit_price = $item->line_unit_price;
                $lineItem->line_amount = $item->line_amount;

                $lineItems[] = $lineItem;
            }

            $valid = Model::validateMultiple($lineItems, [
                'invoice_id', 'line_description',
                'line_amount', 'line_qty', 'line_unit_price',
            ]) && $valid;

            if($valid) {
                $rows = ArrayHelper::getColumn($lineItems, function($element) {
                   return [$element->invoice_id, $element->line_description,
                        $element->line_amount, $element->line_qty, $element->line_unit_price];

                });

                \Yii::$app->db->createCommand()->batchInsert(InvoiceLine::tableName(), [
                    'invoice_id', 'line_description', 'line_amount',
                    'line_qty', 'line_unit_price'
                ], $rows)->execute();

                $transaction->commit();

                return true;
            }
        }

        $transaction->rollBack();

        return false;
    }

    /**
     * Find all invoices that are overdue
     *
     * @return array | array containing the result of the query or an empty string if no record is retrived
     */
    public static function findAllOverdueBills()
    {
        $deleted = static::NOT_DELETED;
        $type = static::TYPE_INVOICE;
        $paid = static::INVOICE_UNPAID;

        $query = 'SELECT DISTINCT `a`.`id`, `a`.`paid`, `a`.`due_date` 
                  FROM ' . self::tableName() . ' `a`
    			  INNER JOIN invoice_age_type ON age_paid = paid
    			  AND (age_paid = 0 AND DATEDIFF(CURDATE(), due_date) >= 121
    			        AND DATEDIFF(CURDATE(), due_date) <= 9000)
    			  WHERE business_id <> 0
    			  AND deleted=:deleted
    			  AND `a`.`type`=:type
    			  AND paid=:paid
    			  ORDER BY `due_date` DESC';

        $data = static::getDb()
            ->createCommand($query)
            ->bindValues([
                ':deleted' => $deleted,
                ':type' => $type,
                ':paid' =>$paid
            ])
            ->queryAll();

        return false === $data ? [] : $data;
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array $rules an array of validation rules
     */
    public function rules()
    {
        return parent::rules();
    }
}
