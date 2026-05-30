<?php

namespace common\models;

use Yii;
use common\helpers\ArrayHelper;
use common\models\invoice\Invoice;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "status".
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property Invoice[] $invoices
 */
class Status extends ActiveRecord
{
    /*
     * Bill statuses
     */
    const STATUS_ACCEPTED = 'bill_accepted';
    const STATUS_DISPUTED = 'bill_disputed';
    const STATUS_PAID = 'bill_paid';
    const STATUS_PENDING = 'bill_pending';
    const STATUS_REJECTED = 'bill_rejected';
    const STATUS_SENT = 'bill_sent';
    const STATUS_UNPAID = 'bill_unpaid';
    const STATUS_REFUND = 'bill_refund';

    /*
     * Ticket statuses
     */
    const STATUS_PLANNING = 'ticket_planning';
    const STATUS_PROCESSING = 'ticket_processing';
    const STATUS_FINALIZED = 'ticket_finalized';
    const STATUS_COMPLETED = 'ticket_completed';

    /*
     * Task statuses
     */
    const STATUS_IN_PROCESSING = 'task_in_process';
    const STATUS_CLOSE = 'task_close';
    const STATUS_OPEN = 'task_open';

    protected $billStatuses = [
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_DISPUTED => 'Disputed',
        self::STATUS_PAID => 'Paid',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_SENT => 'Sent',
        self::STATUS_UNPAID => 'Unpaid',
        self::STATUS_REFUND => 'Refund'
    ];

    protected $ticketStatuses = [
        self::STATUS_PLANNING => 'Planning',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_FINALIZED => 'Finalized',
        self::STATUS_COMPLETED => 'Completed'
    ];

    protected $taskStatuses = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_IN_PROCESSING => 'In Process',
        self::STATUS_CLOSE => 'Close',
    ];

    /**
     * Provides the name of the table
     *
     * @return string $tableName the name of the table
     */
    public static function tableName()
    {
        return '{{%status}}';
    }

    /**
     * Behaviors rules to initialize safe variables (such as created_at, updated_at)
     * prior to saving user's data.
     *
     * @return array $behaviors an array of behaviors
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * Validation rules to apply to class properties
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 30],
        ];
    }

    /**
     * Customized attribute labels in rendered pages
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'   => Yii::t('app', 'Status ID'),
            'name' => Yii::t('app', 'Status Name'),
            'code' => Yii::t('app', 'Status Code'),
        ];
    }

    /**
     * Get statuses for invoice, quote, or utility bill
     * @return array
     */
    public function getBillStatuses()
    {
        return $this->billStatuses;
    }

    /**
     * Get statuses for tickets
     * @return array
     */
    public function getTicketStatuses()
    {
        return $this->ticketStatuses;
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::class, ['status_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function findAllStatuses()
    {
        $query = self::find()->all();
        $data = ArrayHelper::map($query, 'id', 'name');

        return empty($data) ? [] : $data;
    }

    /**
     * @return array
     */
    public static function findTicketStatuses()
    {
        $query = self::find()->where(['like', 'code', 'ticket'])->all();
        $data = ArrayHelper::map($query, 'id', 'name');

        return empty($data) ? [] : $data;
    }

    /**
     * @return array
     */
    public static function findTaskStatuses()
    {
        $query = self::find()->where(['like', 'code', 'task'])->all();
        $data = ArrayHelper::map($query, 'id', 'name');

        return empty($data) ? [] : $data;
    }
}
