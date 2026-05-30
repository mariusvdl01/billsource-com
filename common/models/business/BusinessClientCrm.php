<?php

namespace common\models\business;

use common\helpers\ArrayHelper;
use common\models\Province;
use common\traits\ActiveRecordTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "business_client_crm".
 * @property integer $id
 * @property integer $is_active
 * @property integer $business_id
 * @property string $id_number
 * @property string $email
 * @property string $trading_name
 * @property string $registration_number
 * @property string $registered_name
 * @property string $vat_reg_number
 * @property string $phone_number
 * @property string $address_street
 * @property string $address_region
 * @property integer $province_id
 * @property string $address_code
 * @property string $fax_number
 * @property string $first_name
 * @property string $last_name
 * @property string $mobile
 * @property integer $uses
 * @property boolean $deleted
 * @property boolean $is_business
 * @property string $last_used
 * @property string $created_at
 * @property string $updated_at
 * @property BusinessClient $business
 * @property Province $province
 */
class BusinessClientCrm extends ActiveRecord
{
	const CUSTOMER_DELETED = 1;

	use ActiveRecordTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_client_crm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'mobile','id_number', 'first_name', 'last_name'], 'required'],
            [['registration_number', 'registered_name', 'trading_name'], 'required', 'when' => function($model) {
                return (bool)$model->is_business;
            }, 'whenClient' => "function (attribute, value) {
                return $('#businessclientcrm-is_business').val() == '1';
            }"],
            [['is_active', 'business_id', 'province_id', 'uses'], 'integer'],
        	['email', 'email'],
        	[['deleted', 'is_business'], 'boolean'],
            [['trading_name'], 'string'],
            [['last_used', 'created_at', 'updated_at'], 'safe'],
            [['id_number'], 'string', 'min' => 6, 'max' => 15],
            [['email', 'registration_number', 'registered_name', 'vat_reg_number', 
            	'phone_number', 'address_street', 'address_region', 'address_code', 
            	'fax_number', 'first_name', 'last_name', 'mobile'], 'string', 'max' => 255],
        	[['registration_number', 'vat_reg_number', 'phone_number',
        		'fax_number', 'mobile', 'id_number'], 'filter', 'filter' => function($value) {
        			return str_replace(['/', '-', '_', ' ', '@'], '', $value);
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
        	'first_name' => Yii::t('app', 'First Name'),
        	'last_name' => Yii::t('app', 'Last Name'),
            'is_active' => Yii::t('app', 'Status'),
            'id_number' => Yii::t('app', 'Passport/ID Number'),
            'email' => Yii::t('app', 'Email'),
            'trading_name' => Yii::t('app', 'Trading Name'),
            'registration_number' => Yii::t('app', 'Registration Number'),
            'registered_name' => Yii::t('app', 'Registered Name'),
            'vat_reg_number' => Yii::t('app', 'VAT Reg. Number'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'address_street' => Yii::t('app', 'Address 1'),
            'address_region' => Yii::t('app', 'Address 2'),
            'province_id' => Yii::t('app', 'Province'),
            'address_code' => Yii::t('app', 'Postal Code'),
            'fax_number' => Yii::t('app', 'Fax Number'),
            'mobile' => Yii::t('app', 'Mobile'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessClient::className(), ['id' => 'business_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    /**
     * @param $business_id
     * @return array
     */
    public function findAllByBusinessId($business_id)
    {
    	$query = self::find();
        $customers = $query->where('[[business_id]]=:id', [':id' => $business_id])
    				->andWhere('[[is_active]]=:active', [':active' => true])
    				->andWhere('[[deleted]]=:deleted', [':deleted' => false])
    				->all();

    	$result = ArrayHelper::map($customers, 'id', function($customers, $defaultValue) {
				return !empty($customers['trading_name']) ? $customers['trading_name']
						: $customers['first_name'] . ' ' . $customers['last_name'];
    		}
    	);

    	return $result; 
    }

    /**
     * @param $business_id
     * @return array
     */
    public function findCustomersForClient($business_id)
    {
        $result = array();
        $customers = self::find();
        $customers = $customers->select('email')
            ->where('[[business_id]]=:id', [':id' => $business_id])
            ->andWhere('[[is_active]]=:active', [':active' => 1])
            ->andWhere('[[deleted]]=:deleted', [':deleted' => 0])
            ->asArray()
            ->all();

        if($customers) {
            foreach ($customers as $customer) {
                $result[] = $customer['email'];
            }
        }
        return false === $result ? [] : $result;
    }

    /**
     * @param $crmId
     * @return BusinessClientCrm|null
     */
    public function findCustomerById($crmId)
    {
    	return self::findOne(['id' => $crmId]);
    }

    /**
     * @param $crm_id
     * @return array|BusinessClientCrm|null
     */
    public static function getCustomerData($crm_id)
    {
    	$data = self::findOne(['id' => $crm_id]);
    	
    	return $data === false ? [] : $data;
    }

    /**
     * @param $invoice
     * @param $biller
     */
    public function insertNewCustomer($invoice, $biller)
    {
    	if($biller && $invoice) {
    		$this->email = $invoice->client_email;
            $this->business_id = $biller->id;
            
            if(strlen($invoice->client_id) == 13)
                $this->id_number = $invoice->client_id;
            else
                $this->registration_number = $invoice->client_id;
            
            if($this->registration_number) {
                $this->vat_reg_number = $invoice->client_vat;
                $this->trading_name = $invoice->alt_business_name;
            } else {
                $this->first_name = substr($invoice->alt_business_name, 0,
                    strpos($invoice->alt_business_name, ' '));
            }

            $this->mobile = $invoice->client_mobile;
            
    		$this->save(false);
    	}
    }

    /**
     * @param $crmId
     * @param $invoice
     * @param $business
     */
    public function incrementUses($crmId, $invoice, $business)
    {
    	$crm = self::findOne(['id' => $crmId]);

    	if (isset($crm) && $crm) {
    		$crm->uses += 1;
    		$crm->last_used = (new \DateTime('now'))->format('Y-m-d H:i:s');
    		$crm->save(false);
    	} else {
    		$this->insertNewCustomer($invoice, $business);
    	}
    }
}
