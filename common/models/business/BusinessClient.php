<?php

namespace common\models\business;

use common\models\BusinessProfile;
use common\models\ClientInterface;
use common\models\Company;
use common\models\ecosystem\BusinessEcosystem;
use common\models\invoice\HistoricInvoice;
use common\models\invoice\Invoice;
use common\models\invoice\InvoiceLog;
use common\models\marketplace\BusinessSector;
use common\models\marketplace\BusinessStructure;
use common\models\marketplace\BusinessType;
use common\models\marketplace\Country;
use common\models\Province;
use common\models\sms\SmsHistory;
use common\models\sms\SmsLog;
use common\models\Title;
use common\models\User;
use common\traits\ActiveRecordTrait;
use frontend\models\SignupForm;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "business_client".
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property integer $parent_id
 * @property integer $profile_id
 * @property boolean is_biller
 * @property integer $completed
 * @property string $initials
 * @property string $id_number
 * @property integer $title_id
 * @property string $debit_order_account
 * @property string $debit_order_bank
 * @property string $debit_order_branch
 * @property string $debit_order_branch_code
 * @property string $debit_order_day
 * @property string $debit_order_start_date
 * @property integer $type
 * @property integer $active_users
 * @property string $trading_name
 * @property string $site_url
 * @property integer $country_id
 * @property integer $structure_id
 * @property integer $type_id
 * @property integer $sector_id
 * @property string $registration_number
 * @property string $registered_name
 * @property string $vat_reg_number
 * @property string $phone_number
 * @property string $contact_person
 * @property string $address_street
 * @property string $address_region
 * @property integer $address_province
 * @property string $address_code
 * @property string $fax_number
 * @property string $business_logo
 * @property string $marketing_message
 * @property string $rewards
 * @property string $maximum_limit_sms
 * @property string $created_at
 * @property string $updated_at
 * @property string $credit_terms
 * @property User $user
 * @property Title $title
 * @property Province $province
 * @property BusinessProfile $businessProfile
 * @property BusinessClientCrm[] $businessClientCrms
 * @property Company[] $companies
 * @property HistoricInvoice[] $historicInvoices
 * @property Invoice[] $invoices
 * @property InvoiceLog[] $invoiceLogs
 * @property SmsHistory[] $smsHistories
 * @property SmsLog[] $smsLogs
 * @property BusinessEcosystem $ecosystem
 */
class BusinessClient extends ActiveRecord implements ClientInterface
{
	const BUSINESS_COMPLETED_FIELD = '2';
	const REWARD_POINTS = 50;

    const CATEGORY_BILLER = '2';
    const CATEGORY_DCA = '4';
    const CATEGORY_VAS = '5';
    const CATEGORY_COUNSELLOR = '6';
    const CATEGORY_COLLECTOR = '7';

    const PROFILE_AGENT = '1';
    const PROFILE_BASIC = '2';
    const PROFILE_FREE = '3';
    const PROFILE_INTERNAL = '4';
    const PROFILE_PREMIUM = '5';
    const PROFILE_SELECT = '6';

    use ActiveRecordTrait;

    /**
     * @var string[]
     */
    public static $categories = [
        self::CATEGORY_BILLER => 'Biller',
        self::CATEGORY_DCA => 'Debt Clerk Agency',
        self::CATEGORY_VAS => 'Value Added Service Provider',
        self::CATEGORY_COUNSELLOR => 'Counsellor',
        self::CATEGORY_COLLECTOR => 'Debt Collector',
    ];

    /**
     * @var string[]
     */
    protected $roles = [
        'reader',
        'loader',
        'singleUserAdmin',
        'businessAdmin',
    ];

	/**
	 * Attributes to be completed
	 * @var array $completedAttributes
	 */
	protected $_completedAttributes = [
		'title_id', 'contact_person', 'registration_document',
		'initials', 'id_number', 'trading_name',
		'registration_number', 'registered_name', 'vat_reg_number',
		'address_street', 'address_region', 'address_province',
		'address_code', 'phone_number','fax_number',
		'business_logo', 'country_id', 'structure_id',
        'type_id', 'sector_id', 'service_id',
	];

    /**
     * Provides the name of the table
     * @return string $tableName the name of the table
     */
    public static function tableName()
    {
        return '{{%business_client}}';
    }

    /**
     * Validation rules to apply to class properties
     * @return array | array of validation rules
     */
    public function rules()
    {
        return [
            [['user_id', 'email', 'contact_person', 'id_number'], 'required'],
            [['profile_id'], 'required'],
            [['user_id', 'parent_id', 'title_id', 'profile_id', 'type', 'active_users', 'province_id',
                'rewards', 'maximum_limit_sms', 'is_biller', 'country_id', 'structure_id',
                'type_id', 'sector_id'], 'integer'],
            [['debit_order_start_date', 'created_at', 'updated_at'], 'safe'],
            [['subscribed_date'], 'safe'],
            [['trading_name', 'id_number', 'initials', 'site_url'], 'string'],
            [['debit_order_account', 'debit_order_bank', 'debit_order_branch', 'debit_order_branch_code',
                'debit_order_day', 'registration_number', 'registered_name', 'vat_reg_number', 'phone_number',
                'contact_person', 'address_street', 'address_region', 'address_code', 'fax_number',
                'marketing_message', 'credit_terms', 'site_url'], 'string', 'max' => 255],
            [['business_logo'], 'file', 'extensions' => 'png, jpg, gif, jpeg', 'maxSize' => 2097152],
            [['registration_document'], 'file', 'extensions' => 'pdf', 'maxSize' => 2097152],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'common\models\User',
                'message' => 'This email address is already in use.', 'except' => 'update'],
            ['phone_number', 'unique', 'message' => 'The phone number is already in use', 'except' => 'update'],
            ['phone_number',  'string', 'max' => 10],
            ['registration_number', 'unique', 'message' => 'The registration number is already in use', 'except' => 'update'],
            ['vat_reg_number', 'unique', 'message' => 'The VAT number is already in use', 'except' => 'update'],
            ['fax_number', 'unique', 'message' => 'The Fax number is already in use', 'except' => 'update'],
            [['id_number', 'registration_number', 'vat_reg_number', 'phone_number', 'fax_number',
                'debit_order_account', 'debit_order_branch_code', 'address_code'], 'filter', 'filter' => function($value) {
                return str_replace(['/', '-', '_', ' ', '@', '?', '%', '&', '*', '(', ')', '$', '#'], '', $value);
            }],
            ['site_url', 'filter', 'filter' => function($value) {
                return str_replace(['_', ' ', '@', '?', '%', '&', '*', '(', ')', '$', '#'], '-', $value);
            }],
            ['site_url', 'unique', 'targetClass' => 'common\models\business\BusinessClient', 'message' => 'This URL already exists.'],
            [['country_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Country::class,
                'targetAttribute' => ['country_id' => 'id']
            ],
            [['structure_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => BusinessStructure::class,
                'targetAttribute' => ['structure_id' => 'id']
            ],
            [['type_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => BusinessType::class,
                'targetAttribute' => ['type_id' => 'id']
            ],
            [['sector_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => BusinessSector::class,
                'targetAttribute' => ['sector_id' => 'id']
            ],
        ];
    }

    /**
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['upgrade'] = [
            'debit_order_account', 'debit_order_bank', 'debit_order_branch',
            'debit_order_branch_code', 'debit_order_day', 'profile_id'
        ];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'debit_order_account' 		=> Yii::t('app', 'Debit Order Account'),
            'debit_order_bank' 			=> Yii::t('app', 'Debit Order Bank'),
            'debit_order_branch' 		=> Yii::t('app', 'Debit Order Branch'),
            'debit_order_branch_code' 	=> Yii::t('app', 'Debit Order Branch Code'),
            'debit_order_day' 			=> Yii::t('app', 'Debit Order Day'),
            'debit_order_start_date' 	=> Yii::t('app', 'Debit Order Start Date'),
            'type' 						=> Yii::t('app', 'Type'),
            'profile_id'				=> Yii::t('app', 'Profile'),
            'title_id' 					=> Yii::t('app', 'Title'),
            'active_users' 				=> Yii::t('app', 'Active Users'),
            'trading_name' 				=> Yii::t('app', 'Trading Name'),
            'site_url' 				    => Yii::t('app', 'Microsite URL'),
            'country_id'                => Yii::t('app', 'Country'),
            'structure_id'              => Yii::t('app', 'Business Structure'),
            'type_id'                   => Yii::t('app', 'Business Type'),
            'sector_id'                 => Yii::t('app', 'Business Sector'),
            'registration_number' 		=> Yii::t('app', 'Registration Number'),
            'registered_name' 			=> Yii::t('app', 'Registered Name'),
            'vat_reg_number' 			=> Yii::t('app', 'Vat Reg Number'),
            'phone_number' 				=> Yii::t('app', 'Phone Number'),
            'contact_person' 			=> Yii::t('app', 'Contact Person'),
            'address_street' 			=> Yii::t('app', 'Address Street'),
            'address_region' 			=> Yii::t('app', 'Address Region'),
            'address_province' 			=> Yii::t('app', 'Address Province'),
            'address_code' 				=> Yii::t('app', 'Address Code'),
            'fax_number' 				=> Yii::t('app', 'Fax Number'),
            'business_logo' 			=> Yii::t('app', 'Business Logo'),
            'marketing_message' 		=> Yii::t('app', 'Marketing Message'),
            'rewards' 					=> Yii::t('app', 'Rewards'),
            'maximum_limit_sms' 		=> Yii::t('app', 'Maximum Limit Sms'),
            'credit_terms'				=> Yii::t('app', 'Credit Terms'),
        ];
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        if(!empty($this->trading_name)) {
            return $this->trading_name;
        } else {
            return $this->registered_name;
        }
    }

    /**
     * Finds client by user Id
     * @param integer $id user identity
     * @return static|null active record of user
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }


    /**
     * @param User $user
     * @param SignupForm $form
     * @return bool
     */
    public function signupBusinessUser($user, $form)
    {
        if ($user && $form) {
            $this->user_id = $user->id;
            $this->email = $user->email;
            $this->contact_person = join(' ', [$form->firstname, $form->lastname]);
            $this->type = $form->category;
            $this->trial_start = date("Y-m-d");
            $this->completed = self::BUSINESS_COMPLETED_FIELD;
            $role = $this->getBusinessUserRole();

            if (
                $this->type == self::CATEGORY_COLLECTOR
                || $this->type == self::CATEGORY_DCA
            ) {
                $this->profile_id = self::PROFILE_BASIC;
            }

            try {
                $this->assignBusinessUserRole($user->id, $role);

                return $this->save(false);
            } catch(Exception $e) {
                Yii::error('Business client data not saved to database');
            }
        }

        return false;
    }

    /**
     * Return user role
     * @return mixed|string
     */
    public function getBusinessUserRole()
    {
        $auth = $this->authMan;
        $type = $this->type;
        $role = '';

        if ($auth) {
            switch ($type) {
                case self::CATEGORY_DCA;
                    $role = $this->roles[3];
                    break;
                default:
                    $role = $this->roles[2];
            }
        }

        return $role;
    }

    /**
     * Assign user to role
     * @param int $user_id user ID
     * @param string $role role to assign to user
     */
    public function assignBusinessUserRole($user_id, $role)
    {
        $auth = $this->authMan;

        if ($auth !== null) {
            $role = $auth->getRole($role);
            $auth->assign($role, $user_id);
        }
    }

    /**
     * @param string $attribute attribute name
     * @param bool $resize resize uploaded file if it's image
     */
    public function uploadedFile(string $attribute, bool $resize = true)
    {
        $this->uploadFile(
            $attribute,
            self::WIDTH,
            self::HEIGHT,
            self::UPLOAD_DIR,
            $resize
        );
    }

    /**
     * Checks if user has completed his profile
     * $return boolean | if profile is complete.
     */
    public function hasCompleteProfile()
    {
        $data = $this->getProfileData();

        if (empty($data['address_street'])
            || empty($data['address_region'])
            || empty($data['name'])
            || empty($data['address_code'])
            || empty($data['id_number'])
            || empty($data['phone_number'])
        ) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves user data
     * @return array array containing the result of the query or an empty string if no record is retrieved
     * @throws \yii\db\Exception
     */
    public function getProfileData()
    {
        $query = self::find();
        $data = $query->select([
            'p.name', 't.description', 'bp.free_sms',
            'bp.maximum_limit_invoices', 'bp.maximum_limit_users', 'bp.description AS profile'
        ])
        ->addSelect($this->tableName() . '.*')
        ->joinWith('title t', true)
        ->joinWith('province p', true)
        ->joinWith('businessProfile bp', true)
        ->where('[[user_id]]=:user_id', [':user_id' => $this->user_id])
        ->createCommand()
        ->queryOne();

        return $data === false ? [] : $data;
    }

    public function getProfileDataByWhere($id)
    {
        $query = self::find();
        $data = $query->select([
            'bp.*'
        ])
        ->addSelect($this->tableName() . '.*')
        ->joinWith('title t', true)
        ->joinWith('province p', true)
        ->joinWith('businessProfile bp', true)
        ->where('bp.id ='.$id)
        ->createCommand()
        ->queryOne();

        return $data === false ? [] : $data;
    }

    /**
     * @param Model $model form
     * @return bool if employee information saved successfully
     */
    public function insertEmployee($model)
    {
        $user = new User;
        $user->email = $model->email;
        $user->setPassword($model->password);
        $user->generateAuthKey();
        $user->status = $model->active;
        $user->business_user = true;
        $user->is_activated = User::STATUS_ACTIVATED;

        if ($user->save() && ($id = $user->getPrimaryKey()) !== false) {
            $emp = new self();
            $emp->user_id = $id;
            $emp->parent_id = $model->getParentId();
            $emp->contact_person = $model->full_name;
            $emp->email = $model->email;
            $this->initBusinessInformation($emp);

            try {
                $emp->assignBusinessUserRole($id, $model->role);

                return $emp->save(false);
            } catch(Exception $e) {
                Yii::error('Could not save employee information. Reason :' . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * Initializes
     * @param BusinessClient $emp
     */
    protected function initBusinessInformation(BusinessClient $emp)
    {
        $emp->trading_name = $this->trading_name;
        $emp->registration_number = $this->registration_number;
        $emp->registered_name = $this->registered_name;
        $emp->vat_reg_number = $this->vat_reg_number;
        $emp->business_logo = $this->business_logo;
    }

    /**
     * Inserts new biller (only available to DCA)
     * @param Model $model
     * @return bool
     */
    public function insertBiller($model)
    {
        $user = new User;
        $user->email = $model->email;
        $user->setPassword($model->getPassword());
        $user->generateAuthKey();
        $user->status = $model->active;
        $user->business_user = true;
        $user->is_activated = User::STATUS_ACTIVATED;

        if ($user->save() && ($id = $user->getPrimaryKey()) !== false) {
            $client = new BusinessClient();
            $client->user_id = $id;
            $client->parent_id = $model->getParentId();
            $client->completed = self::BUSINESS_COMPLETED_FIELD;
            $client->setAttributes($model->getAttributes());

            try {
                $client->assignBusinessUserRole($id, $model->role);

                return $this->updateProfile($client);
            } catch(Exception $e) {
                Yii::error('Business client user not saved to database. Reason :' . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * Update user profile information
     * @param null $context application context
     * @return bool updated profile successfully
     */
    public function updateProfilePercentage($context = null)
    {
        $client = !is_null($context) ? $context : $this;
        $completed = $client->completed;
        $attributes = $client->_completedAttributes;

        foreach ($attributes as $attribute) {
            $newValue = $client->getAttribute($attribute);
            $oldValue = $client->getOldAttribute($attribute);
            $reset = !empty($oldValue) && empty($newValue);
            $insert = empty($oldValue) && !empty($newValue);

            $changed = $insert || $reset;

            if ($changed && $insert) {
                $completed += 1;
            }

            if ($changed && $reset) {
                $completed -= 1;
            }
        }

        $client->completed = $completed;
        
        if ($completed > self::EXPECTED_BUSINESS_FIELD) {
            $client->completed = self::EXPECTED_BUSINESS_FIELD;
        }

        return $client->save(false);
    }

    /**
     * @return string
     */
    public static function findUsers()
    {
        $sql = "SELECT a.id, a.email, item_name, status
                FROM user a
                INNER JOIN business_client bc ON bc.user_id = a.id
                INNER JOIN auth_assignment au ON a.id = au.user_id
                WHERE a.id =:id

                UNION

                SELECT IFNULL(u.id, 0) as user_id, IFNULL(u.email, 'New User') AS email,
                IFNULL(item_name, 'Read') AS item_name, IFNULL(status, 0) AS status
                FROM user u
                INNER JOIN business_client bc ON bc.user_id = u.id
                INNER JOIN auth_assignment au ON u.id = au.user_id
                WHERE bc.parent_id
                    IN (SELECT b.id
                    FROM business_client b
                    INNER JOIN user e ON b.user_id = e.id
                    WHERE e.id =:id)
                AND is_biller = 0";

        return $sql;
    }

    /**
     * @return string
     */
    public static function findBillers()
    {
        $sql = "SELECT a.id, a.email, au.item_name, status, bc.*
                FROM user a
                INNER JOIN business_client bc ON bc.user_id = a.id
                INNER JOIN auth_assignment au ON a.id = au.user_id
                WHERE a.id =:id

                UNION

                SELECT IFNULL(u.id, 0) as user_id, IFNULL(u.email, 'New Biller') AS email,
                IFNULL(item_name, 'singleUserAdmin') AS item_name, IFNULL(status, 0) AS status, bc.*
                FROM user u
                INNER JOIN business_client bc ON bc.user_id = u.id
                INNER JOIN auth_assignment d ON u.id = d.user_id
                WHERE bc.parent_id
                    IN (SELECT b.id
                    FROM business_client b
                    INNER JOIN user e ON b.user_id = e.id
                    WHERE e.id =:id)
                AND is_biller = 1";

        return $sql;
    }

    /**
     * @return string
     */
    public static function findBPOCustomers()
    {
        $sql = "SELECT IFNULL(u.id, 0) as user_id, IFNULL(u.email, 'New Biller') AS email,
                IFNULL(item_name, 'singleUserAdmin') AS item_name, IFNULL(status, 0) AS status, bc.*
                FROM user u
                INNER JOIN business_client bc ON bc.user_id = u.id
                INNER JOIN auth_assignment d ON u.id = d.user_id
                WHERE bc.parent_id
                    IN (SELECT b.id
                    FROM business_client b
                    INNER JOIN user e ON b.user_id = e.id
                    WHERE e.id =:id)
                AND is_biller = 1";

        return $sql;
    }

    /**
     * Update employee information
     * @param Model $form form
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public function updateEmployee($form)
    {
        $flash = [];
        $user = $form->getUser();
        $pid = $user->businessClient->parent_id;

        if ($this->validateUser($user, $form, $pid, $flash)) {
            $user->email = $form->email;
            $user->status = $form->active;

            if (!empty($form->password)) {
                $user->setPassword($form->password);
            }

            $client = BusinessClient::findOne(['user_id' => $user->id]);
            $client->contact_person = $form->full_name;
            $client->email = $form->email;

            $sql = "UPDATE auth_assignment SET item_name =:role WHERE user_id =:id ";
            self::findBySql($sql, [':role' => $form->role, ':id' => $user->id])
                ->createCommand()
                ->execute();

            if ($user->save() && $this->updateProfile($client)) {
                return true;
            }
        }

        return $flash;
    }

    /**
     * @param $user
     * @param $model
     * @param $parent_id
     * @param $messages
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function validateUser($user, $model, $parent_id, &$messages)
    {
        $active = 0;
        $currUser = self::findOne(['id'=>$parent_id]);

        if ($currUser === null) {
            return false;
        }

        $sql = 'SELECT `id`, `status` FROM `user` WHERE ' . ' `id` = ' . $user->id;

        if (isset($model->active)) {
            $active = 1;
        }

        $result = self::findBySql($sql)
            ->createCommand()
            ->queryOne();

        if ($result && $active) {
            $recordActive = $result['status'];
            $sql = 'SELECT `profile_code`, `description`, `maximum_limit_users`, COUNT(`a`.`parent_id`) `max_users`
                    FROM `business_client` `a`
                    INNER JOIN `business_profile` `b` ON `a`.`profile_id` = `b`.`id`
                    INNER JOIN `user` `e` ON `e`.`id` = `a`.`user_id`
                    WHERE `a`.`parent_id` =
                                            (SELECT `id`
                                            FROM `business_client` `d`
                                            INNER JOIN `user` `u` ON `u`.`id` = `d`.`user_id`
                                            WHERE `u`.`id` =:id)
                    AND `status` = 1
                    GROUP BY `profile_code`, `maximum_limit_users`';
            $result = self::findBySql($sql, [':id' => $currUser->user_id])
                ->createCommand()
                ->queryOne();

            if ($result !== false
                && isset($result['maximum_limit_users']) && isset($result['max_users'])
                && $result['maximum_limit_users'] < $result['max_users']
            ) {
                $messages[] = 'Maximum number of Active users for '. $result['description'];
                $messages[] = 'To activate this user first make another user inactive for this company.';

                return false;
            } else if ($active != $recordActive && isset($result)
                && isset($result['maximum_limit_users']) && isset($result['max_users'])
                && $result['maximum_limit_users'] == $result['max_users']
            ) {
                $messages[] = 'Maximum number of Active users for '.$result['description'];
                $messages[] = 'To activate this user first make another user inactive for this company.';

                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * @param $form
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public function updateBiller($form)
    {
        $flash = [];
        $user = $form->getUser();
        $pid = $user->businessClient->parent_id;

        if ($this->validateUser($user, $form, $pid, $flash)) {
            $user->email = $form->email;
            $user->status = $form->active;

            $client = BusinessClient::findOne(['user_id' => $user->id]);
            $client->setAttributes($form->getAttributes());

            $sql = "UPDATE auth_assignment
                    SET item_name = '" . $form->role . "'
                    WHERE user_id = " . $user->id;
            self::findBySql($sql)
                ->createCommand()
                ->execute();

            if ($user->save() && $client->save(false)) {
                return true;
            }
        }

        return $flash;
    }

    /**
     * @return array
     */
    public function getDataForCharts()
    {
        return (new Invoice())->getDataForCharts($this);
    }

    /**
     * @return mixed
     */
    public function hydrateEcosystem()
    {
        $result = BusinessEcosystem::find()
            ->where(['business_id' => $this->id])
            ->one();

        if (!is_null($result)) {
            return $result;
        }

        return (new BusinessEcosystem())->loadDefaultValues();
    }

    /**
     * @return ActiveQuery
     */
    public function getEcosystem()
    {

        return $this->hasOne(BusinessEcosystem::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProvince()
    {
    	return $this->hasOne(Province::class, ['id' => 'province_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTitle()
    {
    	return $this->hasOne(Title::class, ['id' => 'title_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
    	return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessProfile()
    {
        return $this->hasOne(BusinessProfile::class, ['id' => 'profile_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessClientCrms()
    {
        return $this->hasMany(BusinessClientCrm::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistoricInvoices()
    {
        return $this->hasMany(HistoricInvoice::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoiceLogs()
    {
        return $this->hasMany(InvoiceLog::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSmsHistories()
    {
        return $this->hasMany(SmsHistory::class, ['business_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSmsLogs()
    {
        return $this->hasMany(SmsLog::class, ['business_id' => 'id']);
    }
}
