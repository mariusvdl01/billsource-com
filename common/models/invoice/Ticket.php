<?php

namespace common\models\invoice;

use common\helpers\ArrayHelper;
use common\models\AuditTrail;
use common\models\document\AbstractDocument;
use common\models\document\AbstractDocument as Bill;
use common\models\Status;
use Yii;

class Ticket extends \common\models\document\AbstractTicketDocument
{
    /**
     * An instance of the audit logger
     *
     * @var AuditTrail $audit this property is read-only
     */
    protected static $audit;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->discount = 0;
        $this->amount = 0.0;
        $this->subtotal = 0.0;
        $this->vat = 0;
        $this->total = 0.0;
        $this->read = 0;
        $this->deleted = 0;
        $this->type = self::TYPE_TICKET;
        $this->paid = self::INVOICE_PAID;
        $this->status_id = Status::findOne(['code' => Status::STATUS_PLANNING])->id;

        $this->initAuditTrailInstance();
    }

    protected function initAuditTrailInstance()
    {
        if (!self::$audit) {
            self::$audit = new AuditTrail;
        }
    }

    /**
     * Handles creating a new ticket when a new Bill is created.
     *
     * @param AbstractDocument $bill
     * @return bool
     */
    public static function createNewTicket(Bill $bill)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $ticket = new static();
        $ticket->fillProperties($bill);
        $ticket->read = 0;
        $ticket->deleted = self::NOT_DELETED;
        $ticket->type = self::TYPE_TICKET;
        $ticket->paid = self::INVOICE_PAID;
        $ticket->status_id = Status::findOne(['code' => Status::STATUS_PLANNING])->id;

        $valid = $ticket->save();

        $lineManager = new InvoiceLineManager($bill);
        $items = $lineManager->getItems();

        if ($valid && $items) {
            list($lineItems, $valid) = self::validateItems($ticket, $items, $valid);

            if ($valid) {
                $rows = ArrayHelper::getColumn($lineItems, function($element) {
                    return [
                        $element->invoice_id,
                        $element->line_description,
                        $element->line_amount,
                        $element->line_qty,
                        $element->line_unit_price
                    ];
                });

                Yii::$app->db->createCommand()->batchInsert(
                    InvoiceLine::tableName(),
                    [
                        'invoice_id',
                        'line_description',
                        'line_amount',
                        'line_qty',
                        'line_unit_price'
                    ],
                    $rows
                )->execute();

                $transaction->commit();

                return $valid;
            }
        }

        $transaction->rollBack();

        return false;
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
