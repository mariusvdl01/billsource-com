<?php

namespace backend\models\clients;

use common\models\business\BusinessClientCrm as Crm;

/**
 * This is the model class for table "{{%business_client_crm}}".
 *
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
 * @property string $last_used
 * @property string $created_at
 * @property string $updated_at
 * @property integer $deleted
 * @property integer $is_business
 */
class BusinessClientCrm extends Crm
{
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
            [['is_active', 'business_id', 'province_id', 'uses', 'deleted', 'is_business'], 'integer'],
            [['business_id', 'email'], 'required'],
            [['trading_name'], 'string'],
            [['last_used', 'created_at', 'updated_at'], 'safe'],
            [['id_number'], 'string', 'max' => 13],
            [['email', 'registration_number', 'registered_name', 'vat_reg_number', 'phone_number', 'address_street',
                'address_region', 'address_code', 'fax_number', 'first_name', 'last_name', 'mobile'], 'string', 'max' => 255],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Crm::className(), 'targetAttribute' => ['business_id' => 'business_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return parent::attributeLabels();
    }

    /**
     * @inheritdoc
     * @return BusinessClientCrmQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BusinessClientCrmQuery(get_called_class());
    }
}
