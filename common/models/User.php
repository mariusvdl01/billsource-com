<?php

namespace common\models;

use common\models\bill\UserBillRequest;
use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\individual\IndividualClient;
use common\traits\ActiveRecordTrait;
use promocat\twofa\behaviors\TwoFaBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model class.
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $business_user
 * @property integer $is_activated
 * @property string $last_login
 * @property string $wallet_address
 * @property string $totp_secret
 * @property string $created_at
 * @property string $updated_at
 * @property BusinessClient $businessClient
 * @property BusinessClientCrm $customer
 * @property IndividualClient $individualClient
 * @property BusinessClient[] $businessClients
 * @property IndividualClient[] $individualClients
 * @property UserBillRequest[] $userBillRequests
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = -1;
    const STATUS_ACTIVATED = 1;
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const TYPE_BUSINESS = 1;
    const TYPE_NOT_BUSINESS = 0;

    use ActiveRecordTrait;

    /**
     * Provides the name of the table
     * @return string
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
            [['username', 'totp_secret'], 'string'],
            [['last_login', 'created_at', 'updated_at'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ENABLED],
            [['totp_secret', 'business_user'], 'default', 'value' => '0'],
            ['status', 'in', 'range' => [self::STATUS_DISABLED, self::STATUS_ENABLED]],
        ];
    }

    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'two_fa' => ['class' => TwoFaBehavior::class]
            ]
        );
    }

    /**
     * Finds user by user Id
     * @param integer $id user identity
     * @return static|null active record of user
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ENABLED]);
    }

    /**
     * Finds user by email
     * @param string $email the email to search
     * @return static|null active record of user
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ENABLED]);
    }

     /**
     * Finds user by id
     * @param string $id the email to search
     * @return static|null active record of user
     */
    public static function findById($userId)
    {
        return static::findOne(['id' => $userId, 'status' => self::STATUS_ENABLED]);
    }

    /**
     * Finds user by username
     * @param string $username the username to search
     * @return static|null active record of user
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ENABLED]);
    }

    /**
     * Finds user by authentication token
     * @param string $token user authentication token
     * @param string $type type of user
     * @return static|null active record of user
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVATED]);
    }

    /**
     * Finds user by password reset token
     * @param string $token password reset token
     * @return static|null active record of user
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ENABLED,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     * @param string $token password reset token
     * @return boolean if password token is valid or otherwise
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }

    /**
     * Gets the user id
     * @return int current user Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets authentication token
     * @return string $auth_key authentication key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates authentication key
     * @param string $authKey the authentication key to validate
     * @return boolean if authentication key is valid or otherwise
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Set username
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->email = $username;
    }

    /**
     * Set email
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set status
     * @param string $status
     */
    public function setStatus($status = self::STATUS_ENABLED)
    {
        $this->status = $status;
    }

    /**
     * Set business user flag
     * $business_user variable
     */
    public function setBusinessUser()
    {
        $this->business_user = self::TYPE_BUSINESS;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getIndividualClient()
    {
        return $this->hasOne(IndividualClient::class, ['user_id' => 'id']);
    }

    public function getBusinessClient()
    {
        return $this->hasOne(BusinessClient::class, ['user_id' => 'id']);
    }

    // check profile
    public function isTrialExpired(): bool
    {
        if (!$this->client->trial_start) {
            return true; // no trial info = expired
        }

        $trialStart = new \DateTime($this->client->trial_start);
        $trialEnd = (clone $trialStart)->modify('+2 months'); // calendar months
        $now = new \DateTime();

        return $now >= $trialEnd;
    }

    public function getClient()
    {
        return $this->business_user !== self::TYPE_BUSINESS ? $this->individualClient : $this->businessClient;
    }

    public function getCustomer()
    {
        return $this->hasOne(BusinessClientCrm::class, ['email' => 'email']);
    }

    /**
     * @return int|mixed
     */
    public function getRewards()
    {
        return $this->getClient()->rewards;
    }

    /**
     * @return ?string
     */
    public function hasTwoFaEnabled(): ?string
    {
        return $this->totp_secret;
    }

    public function generateUsername()
    {
        $this->username = substr($this->email, 0, stripos($this->email, '@'));
    }
}
