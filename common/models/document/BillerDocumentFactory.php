<?php

namespace common\models\document;

use common\models\invoice\Invoice;
use common\models\invoice\Payslip;
use common\models\invoice\Quote;
use common\models\invoice\CashInvoice;
use common\models\invoice\Task;
use common\models\invoice\Ticket;

class BillerDocumentFactory extends DocumentFactory
{
    public function makeInvoice() {
        return new Invoice;
    }

    public function makeQuote() {
        return new Quote;
    }

    public function makeCashInvoice() {
        return new CashInvoice;
    }

    public function makePayslip() {
        return new Payslip;
    }

    public function makeTicket() {
        return new Ticket;
    }

    public function makeTask() {
        return new Task;
    }
}