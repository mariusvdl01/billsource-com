<?php

namespace common\models\document;

use common\events\BillEvent;
use common\helpers\ArrayHelper;
use common\models\AuditTrail;
use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\email\MailManager;
use common\models\individual\IndividualClient;
use common\models\invoice\HistoricInvoice;
use common\models\invoice\HistoricInvoiceLine;
use common\models\invoice\InvoiceAgeType;
use common\models\invoice\InvoiceLine;
use common\models\invoice\TaskLine;
use common\models\invoice\InvoicePayment;
use common\models\invoice\Ticket;
use common\models\payment\PaymentFees;
use common\models\sms\SmsManager;
use common\models\Status;
use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\DataReader;
use yii\db\Exception;
use yii\db\Query;

/**
 * This is the abstract class for different types of document ranging from invoice to quote.
 * @property integer $id
 * @property integer $status_id
 * @property string $type
 * @property integer $business_id
 * @property string $alt_business_name
 * @property boolean $deleted
 * @property string $client_id
 * @property string $client_email
 * @property string $client_mobile
 * @property string $client_vat
 * @property string $reference_number
 * @property string $issue_date
 * @property string $due_date
 * @property double $discount
 * @property double $amount
 * @property integer $paid
 * @property string $comments
 * @property integer $session_id
 * @property string $marketing
 * @property integer $creditor
 * @property double $vat
 * @property double $subtotal
 * @property double $total
 * @property string $pdf
 * @property integer $read
 * @property Status $status
 * @property BusinessClientCrm $customer
 * @property BusinessClient $businessClient
 * @property InvoiceAgeType $invoiceAgeType
 * @property InvoiceLine[] $invoiceLines
 * @property InvoicePayment[] $invoicePayments
 * @property PaymentFees[] $paymentFees
 * @property HistoricInvoice[] $historicInvoices
 * @property HistoricInvoiceLine[] $historicInvoiceLines
 */
abstract class AbstractDocument extends ActiveRecord
{
    /**
     * Bill statuses
     */
	const STATUS_ACCEPTED = 1;
    const STATUS_DISPUTED = 2;
    const STATUS_PAID = 3;
    const STATUS_PENDING = 4;
    const STATUS_REJECTED = 5;
    const STATUS_SENT = 6;
    const STATUS_UNPAID = 7;
    const STATUS_REFUND = 8;
    const STATUS_OPEN = 13;
    const STATUS_INPROGRESS = 13;
    const STATUS_CLOSE = 13;

    /**
     * Bill types
     */
    const TYPE_CASH_INVOICE = 'CNV';
    const TYPE_INVOICE = 'INV';
    const TYPE_QUOTE = 'QTN';
    const TYPE_UTILITY_BILL = 'UTB';
    const TYPE_TICKET = 'TCK';
    const TYPE_PAYSLIP = 'PYP';

    const INVOICE_PAID = 1;
    const INVOICE_UNPAID = 0;
    const DELETED = 1;
    const NOT_DELETED = 0;

    public static $ref_perifx = 'BLSINV';

    /**
     * Bill types description
     */
    protected $typeDescription = [
        self::TYPE_CASH_INVOICE => 'Cash invoice',
        self::TYPE_INVOICE => 'Invoice',
        self::TYPE_QUOTE => 'Quote',
        self::TYPE_UTILITY_BILL => 'Utility bill',
        self::TYPE_TICKET => 'Ticket',
        self::TYPE_PAYSLIP => 'Payslip',
    ];

    /**
     * An instance of the audit logger
     * @var AuditTrail
     */
    protected static $audit;

    /**
     * AbstractDocument constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->initAuditTrailInstance();
    }

    /**
     * Provides the name of the table
     * @return string
     */
    public static function tableName()
    {
        return '{{%document}}';
    }

    /**
     * Behaviors rules to initialize safe variables (such as created_at, updated_at)
     * prior to saving user's data.
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'issue_date',
                'updatedAtAttribute' => false,
                'value' => function () {
                    $now = new \DateTime();

                    return $now->format('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT,
        ];
    }

    /**
     * Validation rules to apply to class properties
     * @return array
     */
    public function rules()
    {
        return [
            [['status_id', 'business_id', 'paid', 'creditor'], 'integer'],
            [['business_id', 'client_email', 'reference_number', 'amount',
                'paid', 'comments', 'due_date'], 'required', 'message' => 'Field is required'],
            [['deleted', 'read', 'paid', 'creditor'], 'boolean'],
            [['deleted', 'read', 'paid', 'creditor'], 'default', 'value' => 0],
            [['issue_date', 'due_date'], 'safe'],
            [['discount', 'amount', 'vat', 'subtotal', 'total'], 'number'],
            [['type'], 'string', 'max' => 3],
            [['alt_business_name'], 'string', 'max' => 255],
            [['client_id'], 'string', 'max' => 30],
            [['client_mobile'], 'string', 'max' => 30],
            [['client_email'], 'string', 'max' => 100],
            [['client_vat'], 'string', 'max' => 50],
            [['reference_number'], 'string', 'max' => 128],
            [['comments', 'marketing'], 'string', 'max' => 1024],
            [['pdf'], 'string', 'max' => 2048],
        ];
    }

    /**
     * Customized attribute labels in rendered pages
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Invoice ID',
            'status_id' => 'Status ID',
            'type' => 'Type',
            'business_id' => 'Business ID',
            'alt_business_name' => 'Alternate Business Name',
            'deleted' => 'Deleted',
            'client_id' => 'Client ID',
            'client_email' => 'Client Email',
            'client_mobile' => 'Client Mobile',
            'client_vat' => 'Client Vat',
            'reference_number' => 'Reference',
            'issue_date' => 'Issue Date',
            'due_date' => 'Due Date',
            'discount' => 'Discount',
            'amount' => 'Amount',
            'paid' => 'Paid',
            'comments' => 'Comments',
            'marketing' => 'Marketing',
            'creditor' => 'Creditor',
            'vat' => Yii::$app->params['tax_label'],
            'total' => 'Total',
            'pdf' => 'Upload file',
            'read' => 'Unread',
        ];
    }

    /**
     * Event handlers for new bill event
     */
    public function init()
    {
        $this->on(BillEvent::BILL_NEW, [$this, 'onNewBillEvent']);
    }

    /**
     *
     */
    protected function initAuditTrailInstance()
    {
        if (!self::$audit) {
            self::$audit = new AuditTrail;
        }
    }

    /**
     * Handles queue of notification email,SMS and creating new ticket
     * triggered by new Bill creation.
     * @param Event $event
     */
    protected function onNewBillEvent(Event $event)
    {
        /** @var AbstractDocument $bill */
        $bill = $event->sender;

        SmsManager::queueNewBillNotification($event);
        MailManager::queueNewBillNotification($event);

        Ticket::createNewTicket($bill);
    }

    /**
     * Find all invoices belonging to this client
     * @deprecated since 1.0.0
     * @param integer $user_id the client id used for searching the database
     * @param integer $paid
     * @param string $type
     * @return string
     */
    public static function findInvoiceByClientId($user_id, $paid = 0, $type = 'INV')
    {
        return "SELECT invoice_payment_id, inv.id, inv.business_id, bc.trading_name,
    		phone_number, contact_person, bc.email, registration_number,
    		reference_number, amount, discount, paid, comments,
    		due_date, minimum_days, debtor_description, image,
    		allow_payment, pdf, `total`
    		FROM " . self::tableName() . " inv
    		LEFT JOIN business_client bc ON inv.business_id = bc.id
    		INNER JOIN invoice_age_type ON age_paid = paid
    		AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    							  AND DATEDIFF(NOW(), due_date) <= maximum_days))
    		LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) a 
    		ON invoice_payment_id = inv.id 
    		WHERE paid = '" . $paid . "' AND deleted = 0
    		AND client_id = '" . $user_id . "' AND inv.type = '" . $type . "'";
    }

    /**
     * Find all invoices belonging to this client
     * @return string
     */
    public static function findInvoiceByUserId()
    {
        return 'SELECT `a`.*, s.name, minimum_days FROM ' . self::tableName() . ' `a`
                INNER JOIN  `business_client` `b` ON `a`.`business_id` = `b`.`id`
                INNER JOIN `user` `u` ON `b`.`user_id` = `u`.`id` 
                INNER JOIN invoice_age_type ON age_paid = paid
                    AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
                    AND DATEDIFF(NOW(), due_date) <= maximum_days))
                INNER JOIN `status` `s` ON `a`.`status_id` = `s`.`id`
                LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `p`
                ON p.invoice_id = a.id
                WHERE deleted=:deleted
                AND u.id=:userId
                AND `a`.`type`=:type
                AND paid=:paid
                AND `a`.`status_id`=:status';
    }

    /**
     * Find all invoices belonging to this client
     * @param integer $client_id
     * @return string containing the query
     */
    public static function findAllBillsForDebtors()
    {
        return "SELECT `a`.*, name, minimum_days FROM " . self::tableName() . " `a`
                INNER JOIN  `business_client` `b` ON `a`.`business_id` = `b`.`id`
                INNER JOIN `user` `u` ON `b`.`user_id` = `u`.`id` 
                INNER JOIN invoice_age_type ON age_paid = paid
                    AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
                    AND DATEDIFF(NOW(), due_date) <= maximum_days))
                INNER JOIN `status` `s` ON `a`.`status_id` = `s`.`id`
                LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `p`
                ON p.invoice_id = a.id
                WHERE deleted =:deleted
                AND u.id =:userId
                AND `a`.`type` =:type";
    }

    /**
     * Find all invoices belonging to this client by status
     * @param integer $business_id the business id used for searching the database
     * @param integer $paid
     * @param $type1
     * @param $type2
     * @return string
     */
    public static function findInvoiceByPaymentStatus($business_id, $paid, $type1, $type2)
    {
        $statusPaid = self::STATUS_PAID;
        $statusUnpaid = self::STATUS_UNPAID;

        return "SELECT inv.id, inv.business_id,
            IFNULL(inv.alt_business_name, inv.client_email) AS business_name,
            phone_number, contact_person, bc.email, registration_number,
            reference_number, amount, discount, paid, comments,
            due_date, minimum_days, debtor_description, image,
            allow_payment, pdf, st.name, `total`
            FROM " . self::tableName() . " inv
            LEFT JOIN business_client bc ON inv.business_id = bc.id
            INNER JOIN status st ON st.id = inv.status_id
            INNER JOIN invoice_age_type ON age_paid = paid
            AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
                                  AND DATEDIFF(NOW(), due_date) <= maximum_days))
            LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) a
                ON a.invoice_id = inv.id
            WHERE paid = '" . $paid . "' 
            AND deleted = 0
            AND inv.status_id IN ($statusPaid, $statusUnpaid)
            AND inv.business_id = '" . $business_id . "' 
            AND (inv.type = '" . $type1 . "' OR inv.type = '" . $type2 . "')";
    }

    /**
     * Find all invoices filtered by state
     * @return string
     */
    public static function findInvoiceByState()
    {
        return "SELECT inv.id, inv.business_id,
    		IFNULL(inv.alt_business_name, inv.client_email) AS business_name,
    		phone_number, contact_person, bc.email, registration_number,
    		reference_number, amount, discount, paid, comments,
    		due_date, minimum_days, debtor_description, image,
    		allow_payment, pdf, st.name, `total`
    		FROM " . self::tableName() . " inv
    		LEFT JOIN business_client bc ON inv.business_id = bc.id
    		INNER JOIN status st ON st.id = inv.status_id
    		INNER JOIN invoice_age_type ON age_paid = paid
    		AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    							  AND DATEDIFF(NOW(), due_date) <= maximum_days))
    		LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) a
    					ON a.invoice_id = inv.id 
    		WHERE (paid=:unpaid OR paid=:paid)
    		AND deleted=:deleted
    		AND inv.business_id=:business_id 
            AND inv.type=:type
    		AND inv.status_id=:status_id";
    }

    /**
     * @param $user_id
     * @param string $type
     * @return array|string
     * @throws Exception
     */
    public static function findInvoiceForHeader($user_id, $type = 'INV')
    {
        $header = [];

        $query = 'SELECT a.*, b.*
    			FROM (SELECT COUNT(inv.id) AS debtors, SUM(total) AS total_debtor_amount
    			FROM business_client bc
    			INNER JOIN ' . self::tableName() . ' inv ON inv.business_id = bc.id
    			INNER JOIN user u ON bc.user_id = u.id
    			WHERE inv.type = \'' . $type . '\' 
    			AND  business_user = 1 
    			AND u.id = ' . $user_id . ' 
    			AND paid = 0 
    			AND deleted = 0 LIMIT 0, 1) a, 
    			(SELECT COUNT( inv.id ) AS credit_bills , SUM(total) AS credit_total 
    			FROM business_client bc
    			INNER JOIN user u ON bc.user_id = u.id 
    			INNER JOIN ' . self::tableName() . ' inv ON inv.type = \'' . $type . '\' 
    			AND bc.email = client_email
    			WHERE deleted = 0 
    			AND business_user = 1 
    			AND u.id = ' . $user_id . '
    			AND paid = 0 
    			AND deleted = 0 LIMIT 0, 1) b';

        $data = self::findBySql($query)->createCommand()->queryAll();
        ArrayHelper::recursive($data, $header);

        return $header == false ? '' : $header;
    }

    /**
     * @param $user_id
     * @param string $type
     * @return array|DataReader
     * @throws Exception
     */
    public static function findOldestBusnessBill($user_id, $type = 'INV')
    {
        $query = 'SELECT `a`.*, `b`.`trading_name`
				FROM ' . self::tableName() . ' `a`
    			INNER JOIN `business_client` `b`
				ON `a`.`business_id` = `b`.`business_id`
    			INNER JOIN `user` `d`
    			ON `b`.`user_id` = `d`.`id`
				WHERE `business_user` = 1 AND `d`.`id` = ' . $user_id . '
    			AND `deleted` = 0 AND `paid` = 0 AND `a`.`type` = \'' . $type . '\'
    			ORDER BY `due_date`, `total` DESC LIMIT 0, 1';

        $data = self::findBySql($query)
            ->createCommand()
            ->queryOne();

        return $data === false ? [] : $data;
    }

    /**
     * @param $user_id
     * @param string $type
     * @return array|string
     * @throws Exception
     */
    public static function findOldestBusinessBillByCreditor($user_id, $type = 'INV')
    {
        $oldest = [];

        $sql = '(SELECT inv.id, trading_name, CONCAT(\'TN\', reference_number) AS reference_number,
    				amount, discount, paid, comments, due_date, `total` 
    			FROM ' . self::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id 
    			WHERE client_id IN 
    							(SELECT trading_name FROM user u 
    							INNER JOIN business_client bc ON u.id = bc.user_id 
    							WHERE business_user = 1 
    							AND u.id = ' . $user_id . ') 
    			AND inv.type = \'' . $type . '\') 
    		
    			UNION 
    					
    			(SELECT inv.id, trading_name, CONCAT(\'RN\', reference_number) AS reference_number, 
    				amount, discount, paid, comments, due_date, `total` 
    			FROM ' . self::tableName() . ' inv
    			INNER JOIN business_client bc  ON inv.business_id = bc.id
    			WHERE client_id IN 
    							(SELECT registration_number FROM user u 
    							INNER JOIN business_client bc ON u.id = bc.user_id 
    							WHERE business_user = 1 
    							AND u.id = ' . $user_id . ') 
    			AND inv.type = \'' . $type . '\') 
    					
    			UNION 
    					
    			(SELECT inv.id, trading_name, CONCAT(\'EM\', reference_number) AS reference_number, 
    				amount, discount, paid, comments, due_date, `total` 
    			FROM ' . self::tableName() . ' inv 
    			INNER  JOIN business_client bc ON inv.business_id = bc.id 
    			WHERE client_email IN 
    								(SELECT bc.email FROM user u 
    								INNER JOIN business_client bc on u.id = bc.user_id 
    								WHERE business_user = 1 AND u.id = ' . $user_id . ') 
    			AND inv.type = \'' . $type . '\')
    			ORDER BY due_date, amount DESC LIMIT 0, 1';

        $data = self::findBySql($sql)->createCommand()->queryAll();
        ArrayHelper::recursive($data, $oldest);

        return $oldest == false ? '' : $oldest;
    }

    /**
     * @return string[]
     */
    public function getTypeDescription()
    {
        return $this->typeDescription;
    }

    /**
     * Find an invoice by its id
     * @param integer $invoiceId the invoice id to be retrieved
     * @return array|string
     * @throws Exception
     */
    public function findInvoiceByInvoiceId($invoiceId)
    {
        $query = self::find();
        $invoice = $query->select([
                'invoice_id', 'IFNULL(trading_name, alt_business_name) AS business_name',
                "CONCAT('ID', reference_number) AS reference_number", 'amount', 'discount',
                'paid', 'comments', 'due_date', 'total',
            ])->innerJoin('business_client', 'invoice.business_id = business_client.business_id')
            ->where('[[invoice_id]]=:invoice_id', [':invoice_id' => $invoiceId])
            ->andWhere("invoice.type = 'INV'")
            ->createCommand()
            ->queryOne();

        return $invoice === false ? '' : $invoice;
    }

    /**
     * @return string
     */
    public function findIndividualBills()
    {
        $sql = 'SELECT DISTINCT `a`.`id`, `a`.`business_id`, `trading_name` , `a`.`read`, 
            `phone_number`,  `contact_person`, `b`.`email`, `registration_number`, `reference_number` , 
            `amount`, `discount` ,`paid`, `comments` , `due_date`, `minimum_days`, `debtor_description`, 
            `image`, `allow_payment`, `pdf`, `vat`, `total`, `name`
            FROM ' . self::tableName() . ' `a` 
            LEFT JOIN `business_client` `b`  ON `a`.`business_id` = `b`.`id` 
            INNER JOIN  `invoice_age_type` ON `age_paid` = `paid`
            INNER JOIN status st ON st.id = a.status_id
            AND (`age_paid` = 1 OR (`age_paid` = 0 AND DATEDIFF(NOW(), `due_date`) >= `minimum_days` 
            AND DATEDIFF(NOW(), `due_date`) <= `maximum_days`))   
            LEFT JOIN (SELECT * FROM `invoice_payment` WHERE `payment_result` = 0) `c` 
            ON `c`.`invoice_id` = `a`.`id`
            WHERE `paid` =:paid 
            AND `deleted` =:deleted
            AND `a`.`type` =:type
            AND `a`.`status_id` =:status
            AND (`client_id` IN (SELECT `id_number` FROM `user` `u`
                                INNER JOIN `individual_client` `d` ON `d`.`user_id` = `u`.`id`  
                                WHERE `business_user` = 0 AND `u`.`id` =:userId)
                 OR `client_email` IN (SELECT `d`.`email` FROM `user` `u` 
                                    INNER JOIN `individual_client` `d` ON `d`.`user_id` = `u`.`id` 
                                    WHERE `business_user` = 0 AND `u`.`id` =:userId)
                 OR `client_mobile` IN (SELECT `mobile` FROM `user` `u` 
                                     INNER JOIN `individual_client` `d` ON `d`.`user_id` = `u`.`id`  
                                     WHERE `business_user` = 0 AND `u`.`id` =:userId))';

        return $sql;
    }

    /**
     * @param $invoice_id
     * @return array|false|DataReader
     * @throws Exception
     */
    public function findSelectedInvoice($invoice_id)
    {
        $query = 'SELECT a.id, trading_name, reference_number, amount, total,
    			phone_number, email, registration_number, contact_person, discount,
    			paid, comments, due_date, client_id, client_email, client_mobile
    			FROM ' . self::tableName() . ' a
    			INNER JOIN business_client b ON a.business_id = b.id
    			WHERE a.id =:id
    			AND a.type =:type
    			AND paid = 0';

        $data = self::findBySql($query, [
                ':id' => $invoice_id,
                ':type' => 'INV'
            ])
            ->createCommand()
            ->queryOne();

        return $data === false ? [] : $data;

    }

    /**
     * @return bool
     */
    public function isStatusChanged()
    {
        $changed = ($this->getOldAttribute('status_id') != intval($this->getAttribute('status_id')));

        if ($changed && (intval($this->getAttribute('status_id') == self::STATUS_PAID))) {
            $this->paid = self::INVOICE_PAID;
        }

        if ($changed && (intval($this->getAttribute('status_id') == self::STATUS_SENT))) {
            $this->status_id = self::STATUS_SENT;
        }

        return $changed;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public function canViewOwnInvoice($user_id)
    {
        return $user_id == self::findOne(['id' => $this->primaryKey])->client_id;
    }

    /**
     * @param $user
     * @return mixed
     * @throws Exception
     */
    public static function getIndividualUnreadBillsCounter($user)
    {
        $query = new Query();
        $client = IndividualClient::findOne(['user_id' => $user->id]);

        $result[self::TYPE_QUOTE] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `deleted`=0
                  AND `status_id`=" . (new Query())
                ->select('id')
                ->from('status')
                ->where(['code' => 'bill_sent'])
                ->scalar()
            ." AND `client_email`='{$client->email}'
               AND `type`='" . self::TYPE_QUOTE . "'"
            ." GROUP BY `type`;"
        )->query();

        $result[self::TYPE_TICKET] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `deleted`=0
                  AND `status_id`=" . (new Query())
                ->select('id')
                ->from('status')
                ->where(['code' => 'bill_sent'])
                ->scalar()
                  ." AND `client_email`='{$client->email}'
                   AND `type`='" . self::TYPE_TICKET . "'"
                ." GROUP BY `type`;"
        )->query();

        $result['CR'] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `deleted`=0
                  AND `paid`=0
                  AND `status_id`=" . (new Query())
                ->select('id')
                ->from('status')
                ->where(['code' => 'bill_sent'])
                ->scalar()
            ." AND ((`client_email`='{$client->email}') OR (`client_id`='{$client->id_number}'))
               AND `type`='" . self::TYPE_INVOICE . "'"
            ." GROUP BY `type`;"
        )->query();

        return $result;
    }

    /**
     * @param $user
     * @return mixed
     * @throws Exception
     */
    public static function getBusinessUnreadBillsCounter($user)
    {
        $query = new Query();
        $client = BusinessClient::findOne(['user_id' => $user->id]);

        $result[self::TYPE_QUOTE] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `status_id`=" . (new Query())
                ->select('id')
                ->from('status')
                ->where(['code' => 'bill_sent'])
                ->scalar()
            ." AND `client_email`='{$client->email}'
               AND `type`='" . self::TYPE_QUOTE . "'"
            ." GROUP BY `type`;"
        )->query();

        $result[self::TYPE_INVOICE] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `status_id`=" . (new Query())
                ->select('id')
                ->from('status')
                ->where(['code' => 'bill_paid'])
                ->scalar()
            ." AND `business_id`={$client->id}
               AND `type`='" . self::TYPE_INVOICE . "'"
            ." GROUP BY `type`;"
        )->query();

        $result[self::TYPE_TICKET] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `status_id`=" . (new Query())
                ->select('id')
                ->from('status')
                ->where(['code' => 'ticket_planning'])
                ->scalar()
            ." AND `business_id`={$client->id}
               AND `type`='" . self::TYPE_TICKET . "'"
            ." GROUP BY `type`;"
        )->query();

        $result['CR'] = $query->createCommand()->setRawSql(
            "SELECT `type`, COUNT(`read`) AS 'counter'
                FROM `document`
                WHERE `read`=0
                  AND `paid`=0
                  AND `client_email`='{$client->email}'
               AND `type`='" . self::TYPE_INVOICE . "'"
            ." GROUP BY `type`;"
        )->query();

        return $result;
    }

    /**
     * @param AbstractDocument $model
     */
    protected function fillProperties($model)
    {
        $this->setAttributes($model->getAttributes());
    }

    /**
     * @param AbstractDocument $model
     * @param $items
     * @param $valid
     * @return array
     */
    protected static function validateItems($model, $items, $valid)
    {
        $lineItems = [];

        foreach ($items as $item) {
            $lineItem = new InvoiceLine;
            $lineItem->invoice_id = $model->id;
            $lineItem->line_qty = $item->line_qty;
            $lineItem->line_description = $item->line_description;
            $lineItem->line_unit_price = $item->line_unit_price;
            $lineItem->line_amount = $item->line_amount;

            $lineItems[] = $lineItem;
        }

        $valid = Model::validateMultiple(
            $lineItems,
            [
                'invoice_id',
                'line_description',
                'line_amount',
                'line_qty',
                'line_unit_price',
            ]) && $valid;

        return [$lineItems, $valid];
    }
    /**
     * @return ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(BusinessClientCrm::class, ['email' => 'client_email']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistoricInvoices()
    {
        return $this->hasMany(HistoricInvoice::class, ['invoice_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessClient()
    {
        return $this->hasOne(BusinessClient::class, ['id' => 'business_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistoricInvoiceLines()
    {
        return $this->hasMany(HistoricInvoiceLine::class, ['invoice_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoiceLines()
    {
        return $this->hasMany(InvoiceLine::class, ['invoice_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoiceAgeType()
    {
        return $this->hasMany(InvoiceAgeType::class, ['age_paid' => 'paid']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoicePayments()
    {
        return $this->hasMany(InvoicePayment::class, ['invoice_id' => 'id'])->andWhere('payment_result = 1');
    }

    /**
     * @return ActiveQuery
     */
    public function getPaymentFees()
    {
        return $this->hasMany(PaymentFees::class, ['payment_index' => 'pay_index'])->via('invoicePayments');
    }


     /**
     * Find all task belonging to this client
     * @return string
     */
    public static function findTaskByUserId()
    {
        return 'SELECT `a`.*, s.name FROM task `a`
                INNER JOIN  `business_client` `b` ON `a`.`business_id` = `b`.`id`
                INNER JOIN `user` `u` ON `b`.`user_id` = `u`.`id` 
                 INNER JOIN `status` `s` ON `a`.`status_id` = `s`.`id`
                WHERE deleted=:deleted
                AND u.id=:userId';
    }

    public function getTaskLines()
    {
        return $this->hasMany(TaskLine::class, ['taskId' => 'id']);
    }
}
