<?php

namespace common\models\invoice;
use common\models\business\BusinessClient;
use common\helpers\ArrayHelper;
use frontend\jobs\TaskNotificationJob;

class Task extends \common\models\document\AbstractTaskDocument
{
    public static function tableName()
    {
        return 'task';
    }
    /**
     * Validation rules to apply to class properties
     *
     * @return array $rules an array of validation rules
     */
    public function rules() {
        return [
            [['status_id', 'business_id'], 'integer'],
            [['tname', 'tname'], 'string'],
            [['business_id', 'client_email', 'reference_number', 'comments', 'tname'], 'required', 'message' => 'Field is required'],
            [['deleted'], 'boolean'],
            [['deleted'], 'default', 'value' => 0],
            [['alt_business_name'], 'string', 'max' => 255],
            [['client_id'], 'string', 'max' => 30],
            [['client_mobile'], 'string', 'max' => 30],
            [['client_email'], 'string', 'max' => 100],
            [['reference_number'], 'string', 'max' => 128],
            [['comments', 'marketing'], 'string', 'max' => 1024],
        ];
    }

      /**
     * Customized attribute labels in rendered pages
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Task ID',
            'tname' => 'Task Name',
            'status_id' => 'Status',
            'business_id' => 'Business ID',
            'alt_business_name' => 'Alternate Business Name',
            'deleted' => 'Deleted',
            'client_id' => 'Client ID',
            'client_email' => 'Client Email',
            'client_mobile' => 'Client Mobile',
            'client_vat' => 'Client Vat',
            'reference_number' => 'Reference',
            'issue_date' => 'Issue Date',
            'comments' => 'Comments',
        ];
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->marketing = '';
        // $this->type = self::TYPE_PAYSLIP;
        // $this->paid = self::INVOICE_PAID;
        $this->status_id = self::STATUS_SENT;
        $this->comments = 'For further enquires, contact our HR department.';
    }

    public static function findOpenTask($bizClient = null)
    {
        $id = \Yii::$app->session['__id'];
        $bizClient = BusinessClient::findOne(['user_id' => $id]);

        $query = Task::find();
        $data = $query->where('business_id=' . $bizClient->id)
            ->andWhere('status_id=13')
            ->all();

        foreach ($data as $item) {
            $item->setAttribute(
                'comments', $item->getAttribute('comments') . ' [' . $item->getAttribute('tname') . ']'
            );
        }

        return ArrayHelper::map($data, 'id', 'tname');
    }

      /**
     * Sends an email with a link, for validating email.
     */
    public function sendEmail($userId, $fname, $lname, $email, $refno)
    {
        \Yii::$app->queue->push(new TaskNotificationJob(
            [
                'email' => $email,
                'firstname' => $fname,
                'lastname' => $lname,
                'userId' => $userId,
                'refNo'=> $refno
            ]
        ));
    }
} 