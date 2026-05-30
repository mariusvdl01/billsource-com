<?php

namespace backend\models\user;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin_user".
 *
 * @property string $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_login
 * @property integer $is_active
 * @property string $rp_token
 * @property string $rp_token_created_at
 * @property integer $failures_num
 * @property string $first_failure
 * @property string $lock_expires
 */
class AdminUser extends \common\models\BaseActiveRecord implements IdentityInterface
{
	const STATUS_ENABLED = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'User ID'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'last_login' => Yii::t('app', 'Last Login'),
            'is_active' => Yii::t('app', 'Is Active'),
            'rp_token' => Yii::t('app', 'Rp Token'),
            'rp_token_created_at' => Yii::t('app', 'Rp Token Created At'),
            'failures_num' => Yii::t('app', 'Failures Num'),
            'first_failure' => Yii::t('app', 'First Failure'),
            'lock_expires' => Yii::t('app', 'Lock Expires'),
        ];
    }
    
    /**
     * Finds user by user Id
     *
     * @param integer $id user identity
     * @return static|null active record of user
     */
    public static function findIdentity($id)
    {
    	return static::findOne(['id' => $id, 'is_active' => self::STATUS_ENABLED]);
    }
     
    /**
     * Finds user by email
     *
     * @param string $email the email to search
     * @return static|null active record of user
     */
    public static function findByEmail($email)
    {
    	return static::findOne(['email' => $email, 'is_active' => self::STATUS_ENABLED]);
    }
    
    /**
     * Finds user by authentication token
     *
     * @param string $token user authentication token
     * @param type $type type of user
     * @return static|null active record of user
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user by password reset token
     *
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
    			'is_active' => self::STATUS_ENABLED,
    	]);
    }
    
    /**
     * Finds out if password reset token is valid
     *
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
    	$timestamp = (int) end($parts);
    	return $timestamp + $expire >= time();
    }
    
    /**
     * Gets the user id
     *
     * @return $id current user Id
     */
    public function getId()
    {
    	return $this->getPrimaryKey();
    }
    
    /**
     * Gets authentication key
     *
     * @return string $rp_token authentication key
     */
    public function getAuthKey()
    {
    	return $this->rp_token;
    }
    
    /**
     * Validates authentication key
     *
     * @param string $authKey the authentication key to validate
     * @return boolean if authentication toek is valid or otherwise
     */
    public function validateAuthKey($authKey)
    {
    	return $this->getRpToken() === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
    	return Yii::$app->security->validatePassword($password, $this->password);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
    	$this->password = Yii::$app->security->generatePasswordHash($password);
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

    public function getFullName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
