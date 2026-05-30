<?php

namespace common\models\document;

abstract class AbstractCashInvoiceDocument extends AbstractDocument
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
}