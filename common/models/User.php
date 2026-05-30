<?php

namespace common\models;

use common\models\bill\UserBillRequest;
use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\individual\IndividualClient;
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

    // TwoFaBehavior removed — promocat/yii2-twofa not available on Packagist
    // Re-enable when package is sourced or replaced with alternative 2FA

    /**
     * Finds user by user Id
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ENABLED]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ENABLED]);
    }

    public static function findById($userId)
    {
        return static::findOne(['id' => $userId, 'status' => self::STATUS_ENABLED]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ENABLED]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVATED]);
    }

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

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function setUsername($username)
    {
        $this->email = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setStatus($status = self::STATUS_ENABLED)
    {
        $this->status = $status;
    }

    public function setBusinessUser()
    {
        $this->business_user = self::TYPE_BUSINESS;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

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

    public function isTrialExpired(): bool
    {
        if (!$this->client || !$this->client->trial_start) {
            return true;
        }
        $trialStart = new \DateTime($this->client->trial_start);
        $trialEnd = (clone $trialStart)->modify('+2 months');
        $now = new \DateTime();
        return $now >= $trialEnd;
    }

    public function getClient()
    {
        return $this->business_user !== self::TYPE_BUSINESS
            ? $this->individualClient
            : $this->businessClient;
    }

    public function getCustomer()
    {
        return $this->hasOne(BusinessClientCrm::class, ['email' => 'email']);
    }

    public function getRewards()
    {
        return $this->getClient()->rewards;
    }

    public function hasTwoFaEnabled(): ?string
    {
        return $this->totp_secret;
    }

    public function generateUsername()
    {
        $this->username = substr($this->email, 0, stripos($this->email, '@'));
    }
}
