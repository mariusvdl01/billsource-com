<?php

namespace common\models;

use common\models\business\BusinessClient;
use common\traits\ActiveRecordTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business_profile".
 * @property integer $id
 * @property string $profile_code
 * @property string $paystack_plan_id
 * @property integer $display_order
 * @property string $description
 * @property double $fee
 * @property integer $maximum_limit_users
 * @property integer $maximum_limit_invoices
 * @property integer $free_sms
 * @property integer $maximum_limit_sms
 * @property integer $auto_notify_email
 * @property integer $auto_notify_sms
 * @property BusinessClient[] $businessClients
 */
class BusinessProfile extends ActiveRecord
{
    const AGENT = 'AGENT';
    const BASIC = 'BASIC';
    const FREE = 'FREE';
    const INTERNAL = 'INTERNAL';
    const PREMIUM = 'PREMIUM';
    const SELECT = 'SELECT';

    use ActiveRecordTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_profile}}';
    }

    /**
     * @return array
     */
    public static function findAllProfiles()
    {
        $query = self::find()
            ->where('display_order <> 0')
            ->orderBy('fee', 'ASC')
            ->all();
        $data = ArrayHelper::map($query, 'id', 'description');

        return !isset($data) ? [] : $data;
    }
      /**
     * @return array
     */
    public static function findProfileById($id)
    {

        $query = self::find()
            ->where('id ='.$id)
            ->all();
        $data = [];
        foreach($query as $value){
            $data['id'] = $value['id'];
            $data['description'] = $value['description'];
            $data['plan'] = $value['paystack_plan_id'];
            $data['fee'] = $value['fee'];
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_code'], 'required'],
            [['display_order', 'maximum_limit_users', 'maximum_limit_invoices', 'free_sms', 'maximum_limit_sms',
                'auto_notify_email', 'auto_notify_sms'], 'integer'],
            [['fee'], 'number'],
            [['profile_code', 'description', 'paystack_plan_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                     => Yii::t('app', 'Profile ID'),
            'profile_code'           => Yii::t('app', 'Profile Code'),
            'display_order'          => Yii::t('app', 'Display Order'),
            'description'            => Yii::t('app', 'Description'),
            'fee'                    => Yii::t('app', 'Fee'),
            'maximum_limit_users'    => Yii::t('app', 'Maximum Limit Users'),
            'maximum_limit_invoices' => Yii::t('app', 'Maximum Limit Invoices'),
            'free_sms'               => Yii::t('app', 'Free Sms'),
            'maximum_limit_sms'      => Yii::t('app', 'Maximum Limit Sms'),
            'auto_notify_email'      => Yii::t('app', 'Auto Notify Email'),
            'auto_notify_sms'        => Yii::t('app', 'Auto Notify Sms'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessClients()
    {
        return $this->hasMany(BusinessClient::class, ['profile_id' => 'id']);
    }
}
