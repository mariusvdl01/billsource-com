<?php

namespace common\models\invoice;

class Payslip extends \common\models\document\AbstractPayslipDocument
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->marketing = '';
        $this->type = self::TYPE_PAYSLIP;
        $this->paid = self::INVOICE_PAID;
        $this->status_id = self::STATUS_SENT;
        $this->comments = 'For further enquires, contact our HR department.';
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
