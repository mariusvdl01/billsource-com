<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property integer $id
 * @property string $company_code
 * @property integer $debit_order_default_day
 * @property integer $active_users
 * @property string $trading_name
 * @property string $registration_number
 * @property string $registered_name
 * @property string $vat_reg_number
 * @property string $phone_number
 * @property string $email
 * @property string $address_street
 * @property string $address_region
 * @property integer $address_province
 * @property string $address_code
 * @property string $fax_number
 * @property string $business_logo
 * @property string $marketing_message
 * @property string $rewards
 * @property integer $default_notify_day
 * @property integer $default_notify_hour
 * @property integer $max_emails_hour
 * @property string $root_dir
 * @property string $vault_dir
 * @property string $created_at
 * @property string $updated_at
 */
class Company extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_code'], 'required'],
            [['debit_order_default_day', 'active_users', 'address_province', 'rewards', 'default_notify_day', 'default_notify_hour', 'max_emails_hour'], 'integer'],
            [['trading_name', 'business_logo'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_code', 'registration_number', 'registered_name', 'vat_reg_number', 'phone_number', 'email', 'address_street', 'address_region', 'address_code', 'fax_number', 'marketing_message', 'root_dir', 'vault_dir'], 'string', 'max' => 255],
            [['company_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Company ID'),
            'company_code' => Yii::t('app', 'Company Code'),
            'debit_order_default_day' => Yii::t('app', 'Debit Order Default Day'),
            'active_users' => Yii::t('app', 'Active Users'),
            'trading_name' => Yii::t('app', 'Trading Name'),
            'registration_number' => Yii::t('app', 'Registration Number'),
            'registered_name' => Yii::t('app', 'Registered Name'),
            'vat_reg_number' => Yii::t('app', 'Vat Reg Number'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'address_street' => Yii::t('app', 'Address Street'),
            'address_region' => Yii::t('app', 'Address Region'),
            'address_province' => Yii::t('app', 'Address Province'),
            'address_code' => Yii::t('app', 'Address Code'),
            'fax_number' => Yii::t('app', 'Fax Number'),
            'business_logo' => Yii::t('app', 'Business Logo'),
            'marketing_message' => Yii::t('app', 'Marketing Message'),
            'rewards' => Yii::t('app', 'Rewards'),
            'default_notify_day' => Yii::t('app', 'Default Notify Day'),
            'default_notify_hour' => Yii::t('app', 'Default Notify Hour'),
            'max_emails_hour' => Yii::t('app', 'Max Emails Hour'),
            'root_dir' => Yii::t('app', 'Root Dir'),
            'vault_dir' => Yii::t('app', 'Vault Dir'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
