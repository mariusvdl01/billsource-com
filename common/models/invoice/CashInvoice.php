<?php

namespace common\models\invoice;


class CashInvoice extends \common\models\document\AbstractCashInvoiceDocument
{
    public function __construct($config = []) {
        $this->type = self::TYPE_CASH_INVOICE;
        $this->paid = self::INVOICE_PAID;
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array $rules an array of validation rules
     */
    public function rules() {
        return parent::rules();
    }
}
