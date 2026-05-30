<?php
namespace common\models;

use Yii;
use yii\base\Model;

//use frontend\models\User;

/**
 * Login form
 *
 * @author Kenneth Onah
 */
class LoginForm extends Model
{
    /**
     * Registered user email
     *
     * @var string $email user email
     */
    public $email;
    /**
     * Registered user password
     *
     * @var string $password user password
     */
    public $password;
    /**
     * If user wants to be remembered
     *
     * @var boolean $rememberMe if user want to remembered across multiple request
     */
    public $rememberMe = true;
    /**
     * An instance of the current user
     *
     * @var string $firstname user First bname
     */
    private $user = false;

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
            ['email', 'exist', 'targetClass' => 'common\models\User', 'message' => 'Incorrect email or password.'],
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
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 5 * 60 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::findByEmail($this->email);
        }

        return $this->user;
    }

    /**
     * Sends an email.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = $this->getUser();

        if ($this->validate()) {
            return Yii::$app->mailer->compose([
                'html' => 'loginNotification-html',
                'text' => 'loginNotification-text'
            ],
                ['user' => $user]
            )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($this->email)
                ->setSubject('Billsource - Login notification')
                ->send();
        }

        return false;
    }

    /**
     * Update user last_login attribute and save in the database
     */
    public function updateLastLogin()
    {
        $user = $this->getUser();
        if ($user) {
            Yii::$app->session['__last_login'] = $user->last_login;
            $user->last_login = Yii::$app->formatter->asDatetime(date_create(), "php:Y-m-d H:i:s");
            $user->save(false);
        }
    }
}