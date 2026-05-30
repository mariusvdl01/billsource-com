<?php

namespace common\models\individual;

use common\models\Assistance;
use common\models\invoice\Invoice;
use common\models\invoice\InvoicePayment;
use common\models\Province;
use common\models\Title;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "individual_client".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property integer $completed
 * @property integer $title_id
 * @property string $first_name
 * @property string $last_name
 * @property string $initials
 * @property string $id_number
 * @property string $med_aid_name
 * @property string $med_aid_number
 * @property string $address_street
 * @property string $address_region
 * @property integer $address_province_id
 * @property string $address_code
 * @property string $home_telephone
 * @property string $office_telephone
 * @property string $mobile
 * @property integer $submit_assistance
 * @property integer $assistance_agree_terms
 * @property integer $assistance_update
 * @property integer $assistance_contact
 * @property integer $rewards
 * @property string $photo
 * @property string $alternate_email
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property Title $title
 * @property Province $province
 * @property Assistance[] $assistances
 * @property Invoice[] $invoices
 * @property IndividualFinancial[] $individualFinancials
 * @property IndividualReading[] $individualReadings
 * @property InvoicePayment[] $invoicePayments
 */
class IndividualClient extends \common\models\BaseActiveRecord
{
	const REQUIRED_FIELD = '3';
	const REWARD_POINTS = '50';

    protected $roles = ['individual'];
	/**
	 * Attributes to be completed
	 * 
	 * @var array $completedAttributes
	 */
	protected $_completedAttributes = [
		'email', 'title_id', 'first_name', 'last_name',
		'initials', 'id_number', 'med_aid_name', 'med_aid_number',
		'address_street', 'address_region', 'address_province_id',
		'address_code', 'home_telephone', 'office_telephone',
		'mobile', 'photo'
	];

    /**
	 * Provides the name of the table
	 *
	 * @return string $tableName the name of the table
	 */
    public static function tableName()
    {
        return '{{%individual_client}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'email', 'first_name', 'last_name', 'id_number'], 'required'],
            ['email', 'email'],
            [['user_id', 'completed', 'title_id', 'province_id', 'submit_assistance',
            		'assistance_agree_terms', 'assistance_update', 'assistance_contact', 'rewards'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name', 'address_street', 'address_region'], 'string', 'max' => 255],
        	['address_code', 'string', 'max' => '8'],
        	[['id_number'], 'string', 'min' => 6, 'max' => 15, 'message' => 'Invalid ID Number'],
            [['initials'], 'string', 'max' => 255],
            [['med_aid_name'], 'string', 'max' => 30],
            [['med_aid_number'], 'string', 'max' => 40],
            [['home_telephone', 'office_telephone', 'mobile'], 'string', 'max' => 12, 'message' => 'Phone number format (0744385851)'],
        	[['photo'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 2097152],
        	[['alternate_email'], 'string', 'max' => 30],
        	['email', 'unique', 'targetClass' => 'common\models\User', 'message' => 'This email address has already been taken.', 'except' => 'update'],
        	['mobile', 'unique', 'message' => 'The phone number has already been taken', 'except' => 'update'],
        	['id_number', 'unique', 'message' => 'The ID/Passport number has already been taken', 'except' => 'update'],
        	[['id_number', 'home_telephone', 'office_telephone', 'mobile'], 'filter', 'filter' => function($value) {
        			return str_replace(['/', '-', '_', ' ', '@', '?', '%', '&', '*', '(', ')', '$', '#'], '', $value);
        		}
        	],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title_id' => Yii::t('app', 'Title ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'initials' => Yii::t('app', 'Initials'),
            'id_number' => Yii::t('app', 'ID / Passport Number'),
            'med_aid_name' => Yii::t('app', 'Med Aid Name'),
            'med_aid_number' => Yii::t('app', 'Med Aid Number'),
            'address_street' => Yii::t('app', 'Billing Address Street'),
            'address_region' => Yii::t('app', 'Billing Address Region'),
            'address_province_id' => Yii::t('app', 'Billing Address Province ID'),
            'address_code' => Yii::t('app', 'Billing Address Code'),
            'home_telephone' => Yii::t('app', 'Home Telephone'),
            'office_telephone' => Yii::t('app', 'Office Telephone'),
            'mobile' => Yii::t('app', 'Mobile No.'),
        	'submit_assistance' => 'Submit Assistance',
        	'assistance_agree_terms' => 'Assistance Agree Terms',
        	'assistance_update' => 'Assistance Update',
        	'assistance_contact' => 'Assistance Contact',
        	'rewards' => 'Rewards',
        	'photo' => 'Photo',
        	'alternate_email' => 'Alternate Email',
        ];
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function signupIndividualUser($user, $form) 
    {    	
    	if($user && $form) {
	    	$this->user_id = $user->id;
	    	$this->email = $user->email;
	    	$this->first_name = $form->firstname;
	    	$this->last_name = $form->lastname;
	    	$this->completed = self::REQUIRED_FIELD;
	    	$auth = Yii::$app->authManager;
	    	$role = $auth->getRole('individual');
            try {
	    	  $auth->assign($role, $user->id);
              // Fixme - title_id default value
	    	  return $this->save(false);
            } catch(Exception $e) {
                Yii::error('Individual client data not saved to database');
            }
    	}
    	return false;
    }

    /**
     * Checks if user has completed his profile
     *
     * $return boolean if profile is complete.
     */
    public function hasCompleteProfile()
    {
        $profile = $this->getProfileData();
        //var_dump($profile);exit;
        if(empty($profile->address_street)
            || empty($profile->address_region)
            || empty($profile->province->name)
            || empty($profile->address_code)
            || empty($profile->id_number)
            || empty($profile->mobile))
            return false;

        return true;
    }
    
    /**
     * Retrieves user data 
     * 
     * @param integer $user_id the id of the current user
     *
     * @return ActiveRecord containing the result of the query or an empty string if no record is retrieved
     */
    public function getProfileData() {
    	$query = self::find();
    	$data = $query->select(['t.description', 'p.name'])
            ->addSelect(self::tableName() . '.*')
            //->addSelect(IndividualFinancial::tableName()'.*')
            ->joinWith('user u', true, 'LEFT JOIN')
            //->joinWith('individualFinancials', true, 'LEFT JOIN')
            ->joinWith('title t', true, 'LEFT JOIN')
            ->joinWith('province p', true, 'LEFT JOIN')
            ->where('[[u.id]]=:id', [':id' => $this->user_id])
            ->one();
    	
    	return $data === false ? null : $data;
    }
    
    public function getPaidInvoice()
    {
    	$query = self::find();
    	$data = $query->select(['SUM(payment_amount) AS `total_spend`'])
    		->joinWith('user', true, 'LEFT JOIN')
    		->joinWith('invoicePayments', true, 'LEFT JOIN')
    		->where('[[user.id]]=:id', [':id' => $this->user_id])
    		->andWhere(['payment_result' => '1'])
    		->createCommand()
    		->queryOne();
    	 
    	return $data === false ? null : $data;
    }
    
    public function updateProfile()
    {
    	$count = 0;
    	$completed = $this->completed;
    	$attributes = $this->_completedAttributes;
    	
    	foreach($attributes as $attribute) {
    		$newValue = $this->getAttribute($attribute);
    		$oldValue = $this->getOldAttribute($attribute);
    		$changed = ( $newValue !== $oldValue);
    		
    		if($changed && empty($oldValue) && !empty($newValue)) {
    			$completed += 1;
    		} 
    		if ($changed && empty($newValue) && !empty($oldValue)) {
    			$completed -= 1;
    		}
    	}
    	if($completed <= self::EXPECTED_FIELD)
    		$this->completed = $completed;
    	
    	return $this->save();
    }
    
	public function uploadImageFile()
    {
        $this->uploadLogo();
    }
    
    /**
     * Find oldest invoice belonging to this client
     *
     * @param integer $client_id the client id used for searching the database
     * @return array containing the result of the query or an empty string if no record is retrived
     */
    public function findOldestInvoice($type = 'INV') {
    	$query = 'SELECT `a`.`id`, IFNULL(`trading_name`, `alt_business_name`) `business_name`, 
    			`reference_number`, `total`, `comments`, `due_date`, `paid`
    			FROM ' . Invoice::tableName() . ' `a`
    			INNER JOIN `business_client` `b` ON `a`.`business_id` = `b`.`id` 
    			WHERE `paid` = 0 AND `a`.`type` =:type 
    			AND (`client_id`
    							IN (SELECT `id_number` FROM `user` `u` 
    								INNER JOIN `individual_client` `c` ON `u`.`id` = `c`.`user_id`
    								WHERE `business_user` = 0 AND `u`.`id` =:id)
    				OR `client_email`
    							IN (SELECT `c`.`email` FROM `user` `u` 
    								INNER JOIN `individual_client` `c` ON `u`.`id` = `c`.`user_id`
    								WHERE `business_user` = 0 AND `u`.`id` =:id)
    				OR `client_mobile`
    							IN (SELECT `mobile` FROM `user` `u` 
    								INNER JOIN `individual_client` `c` ON `u`.`id` = `c`.`user_id`
    								WHERE `business_user` = 0 AND `u`.`id` =:id))
    		ORDER BY `due_date`, `total` DESC LIMIT 0, 1';
    	
    	$oldestInvoice = Invoice::findBySql($query, [':type' => $type, ':id'=>$this->user_id])
				    		->createCommand($this->db)
				    		->queryOne();
    	 
    	return $oldestInvoice === false ? [] : $oldestInvoice;
    }
    
    /**
     * Find total amount due to creditors from this client
     *
     * @param integer $user_id the user id of current user
     * @return array containing the result of the query or an empty array if no record is retrieved
     */
    public function getTotalOutstandingBills() {
    	$query = 'SELECT COUNT(`a`.`id`) AS bills, SUM(`amount`) AS total_amount, `client_id` 
    			FROM ' . Invoice::tableName() . ' `a`
    			WHERE  `deleted` = 0  
    			AND `type` = \'INV\' 
    			AND `paid` = 0 
    			AND ((`client_id` IN 
    					(SELECT `id_number` FROM `user` `u` 
    					INNER JOIN `individual_client` `b` ON `u`.`id` = `b`.`user_id`
    					WHERE `business_user` = 0 
    					AND `u`.`id` =:id)) 
    				OR (`client_email` IN 
    					(SELECT `b`.`email` FROM `user` `u` 
    					INNER JOIN `individual_client` `b` ON `u`.`id` = `b`.`user_id` 
    					WHERE `business_user` = 0 
    					AND `u`.`id` =:id)) 
    				OR (`client_mobile` IN 
    					(SELECT `mobile` FROM `user` `u` 
    					INNER JOIN `individual_client` `b` ON `u`.`id` = `b`.`user_id` 
    					WHERE `business_user` = 0 
    					AND `u`.`id` =:id)))
    			GROUP BY client_id
    			LIMIT 0, 1';
    	
    	$outstanding = Invoice::findBySql($query, [':id'=>$this->user_id])
					    	->createCommand($this->db)
					    	->queryOne();
    	
    	//$user = self::findIdentity($this->user_id);
    	$model = IndividualFinancial::findOne(['individual_id' => $this->id]);
    	
    	if($outstanding && isset($outstanding['total_amount'])) {
    		if(!$model) {
    			$model = new IndividualFinancial;
    			$model->individual_id = $this->id;
    			$model->outstanding_bills = $outstanding['total_amount'];
    		} else {
    			$model->outstanding_bills = $outstanding['total_amount'];
    		}
    		$model->save();
    	}
    	
    	return $outstanding === false ? [] : $outstanding;
    }
    
    /**
     * Finds client by user Id
     *
     * @param integer $id user identity
     *
     * @return array|ActiveRecord active record of user
     */
    public static function findIdentity($id)
    {
    	return static::find()->where('[[user_id]]=:id', [':id' => $id])->one();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssistances()
    {
    	return $this->hasMany(Assistance::className(), ['individual_id' => 'individual_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividualFinancials()
    {
        return $this->hasMany(IndividualFinancial::className(), ['individual_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividualReadings()
    {
        return $this->hasMany(IndividualReading::className(), ['individual_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicePayments()
    {
        return $this->hasMany(InvoicePayment::className(), ['user_id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
    	return $this->hasMany(Invoice::className(), ['client_id' => 'email']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitle()
    {
    	return $this->hasOne(Title::className(), ['id' => 'title_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
    	return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
