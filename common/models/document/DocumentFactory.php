<?php

namespace common\models\document;


abstract class DocumentFactory {
	abstract function makeInvoice();
    abstract function makeQuote();
}