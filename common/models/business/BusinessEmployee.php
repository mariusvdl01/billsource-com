<?php

namespace common\models\business;

use common\helpers\ArrayHelper;
use common\models\Province;
use Yii;

/**
 * This is the model class for table "{{%business_employee}}".
 *
 * @property integer $emp_id
 * @property integer $is_active
 * @property integer $business_id
 * @property string $id_number
 * @property string $email
 * @property string $address_street
 * @property string $address_region
 * @property integer $address_province
 * @property string $address_code
 * @property string $first_name
 * @property string $last_name
 * @property string $mobile
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Province $province
 */
class BusinessEmployee extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'business_id', 'province_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'id_number', 'mobile', 'address_street', 'address_region'],
                'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_number'], 'string', 'max' => 13],
            [['email', 'address_street', 'address_region', 'address_code', 'first_name', 'last_name', 'mobile'],
                'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emp_id' => Yii::t('app', 'Emp ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'business_id' => Yii::t('app', 'Business ID'),
            'id_number' => Yii::t('app', 'ID Number'),
            'email' => Yii::t('app', 'Email'),
            'address_street' => Yii::t('app', 'Street Address'),
            'address_region' => Yii::t('app', 'City/Town/Suburb'),
            'province_id' => Yii::t('app', 'Province'),
            'address_code' => Yii::t('app', 'Postal Code'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'mobile' => Yii::t('app', 'Mobile'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    public static function getEmployees($business_id)
    {
        $result = static::find()->where('[[business_id]]=:id', [':id' => $business_id])->all();

        return ArrayHelper::map($result, 'id', function($model) {
            return $model->first_name . ' ' . $model->last_name;
        });
    }

    public static function getEmployeeData($emp_id)
    {
        $data = self::findOne(['id' => $emp_id]);

        return $data === false ? [] : $data;
    }
}
