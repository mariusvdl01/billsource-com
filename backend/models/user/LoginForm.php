<?php 
namespace backend\models\user;

use Yii;
use backend\models\user\AdminUser;

class LoginForm extends \common\models\LoginForm
{
	/**
	 * An instance of the current user
	 *
	 * @var string $firstname user First bname
	 */
	private $_user = false;

    /**
     * Validation rules to apply to class properties
     *
     * @return array array of validation rules
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['email', 'exist', 'targetClass' => 'backend\models\user\AdminUser', 'message' => 'Incorrect email or password.'],
            ['password', 'validatePassword'],
        ];
    }
	
	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Incorrect email or password.');
			}
		}
	}
	
	/**
	 * Logs in a user using the provided email and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 300 : 300);
		} else {
			return false;
		}
	}
	
	/**
	 * Finds user by [[email]]
	 *
	 * @return AdminUser|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$this->_user = AdminUser::findByEmail($this->email);
		}
	
		return $this->_user;
	}
	
	/**
	 * Sends an email.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail()
	{
		/* @var $user AdminUser */
		$user = AdminUser::findOne([
				'email' => $this->email,
		]);
	
		if ($user) {
			return Yii::$app->mailer->compose(['html' => 'loginNotification-html', 'text' => 'loginNotification-text'], ['user' => $user])
			->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
			->setTo($this->email)
			->setSubject('Billsource Admin - Login notification')
			->send();
		}
		return false;
	}
}
?>