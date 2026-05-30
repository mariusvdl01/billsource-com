<?php

namespace backend\models\customer;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $business_user
 * @property integer $is_activated
 * @property string $last_login
 * @property string $created_at
 * @property string $updated_at
 */
class Customer extends \common\models\User
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'auth_key', 'password_hash'], 'required'],
            [['status', 'business_user', 'is_activated'], 'integer'],
            [['last_login', 'created_at', 'updated_at'], 'safe'],
            [['email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'User ID'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'business_user' => Yii::t('app', 'Type'),
            'is_activated' => Yii::t('app', 'Activated?'),
            'last_login' => Yii::t('app', 'Last Login'),
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerQuery(get_called_class());
    }
}
