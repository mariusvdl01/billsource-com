<?php
/**
 * Created by PhpStorm.
 * User: kenny
 * Date: 3/5/17
 * Time: 12:22 AM
 */

namespace common\events;

use common\models\AuditTrail;
use common\models\business\BusinessClient;
use yii\base\Event;

class BillEvent extends Event
{
    /**
     * Event is triggered when a new bill is inserted into the database
     */
    const BILL_NEW = 'billNew';

    /**
     * Event is triggered when an existing bill is updated
     */
    const BILL_UPDATE = 'billUpdate';

    /**
     * @var BusinessClient owner of the bill
     */
    public $biller;

    /**
     * @var AuditTrail audit instance
     */
    public $audit;
}