<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    /**
     * Full name of user
     *
     * @var string $name user email
     */
    public $name;
    /**
     * Email for user
     *
     * @var string $email user email
     */
    public $email;
    /**
     * Subject of message
     *
     * @var string $subject user email
     */
    public $subject;
    /**
     * Message body
     *
     * @var string $body user email
     */
    public $body;
    /**
     * Verify code to prevent spam bots submitting spam emails
     *
     * @var string $verifyCode verification code
     */
    public $verifyCode;

    /**
     * Validation rules to apply to class properties
     *
     * @return array array of validation rules
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'captchaAction' => 'default/captcha'],
        ];
    }

    /**
     * Customized attribute labels in rendered pages
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string $email the target email address
     *
     * @return boolean whether the model passes validation
     */
    public function sendEmail()
    {
        //if($this->validate()) {
            return Yii::$app->mailer->compose()
                ->setTo([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setFrom($this->email)
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();
        //}
        //return false;
    }
}
