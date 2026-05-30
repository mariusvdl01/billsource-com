<?php

namespace common\models\invoice;

use common\events\BillEvent;
use common\models\Status;
use yii\db\Exception;
use yii\log\Logger;

class Quote extends \common\models\document\AbstractQuoteDocument
{
    public function __construct($config = []) 
    {
        parent::__construct($config);

        $this->type = self::TYPE_QUOTE;
        $this->paid = self::INVOICE_UNPAID;
        $this->status_id = self::STATUS_SENT;
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

    public function acceptQuote()
    {
        if($this) {
            try {
                $valid = Invoice::createNewInvoice($this);
                //$valid = Ticket::createNewTicket($this) && $valid;
                if($valid) {
                    $event = new BillEvent();
                    $event->biller = $this->businessClient;
                    $event->audit = self::$audit;
                    $this->trigger(BillEvent::BILL_NEW, $event);

                    $this->status_id = Status::findOne(['code' => Status::STATUS_ACCEPTED])->id;
                    $this->save();

                    return true;
                }
            } catch (Exception $e) {
                \Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_TRACE);
                return false;
            }
        }
        return false;
    }
}