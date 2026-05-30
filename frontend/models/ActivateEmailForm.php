<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;
use common\helpers\Billsource;
use common\models\business\BusinessClient;
use common\models\individual\IndividualClient;

/**
 * User activation model
 */
class ActivateEmailForm extends Model
{
    /**
     * @var string $token authentication key
     */
    public $token;
    
    /**
     * @var \common\models\User $user instance of the current user
     */
    private $user;


    /**
     * Creates a model given an email and token.
     *
     * @param  string $email
     * @param  string $token
     * @param  array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($email, $token, $config = [])
    {
        if (empty($email) || !is_string($email)) {
            throw new InvalidParamException('Email cannot be blank.');
        }
        if (empty($token) || !is_string($token)) {
        	throw new InvalidParamException('Token cannot be blank.');
        }
        $this->user = User::findByEmail($email);
        if (!$this->user) {
            throw new InvalidParamException('Invalid email.');
        }
        $this->token = $token;
        parent::__construct($config);
    }

    /**
     * Activate email.
     *
     * @return boolean if email is activated.
     */
    public function activateEmail()
    {
    	$client = null;
        $user = $this->user;
        $valid = $user->validateAuthKey($this->token);

        if($valid) {
            $user->refresh();
        	$user->is_activated = User::STATUS_ACTIVATED;
        	if(!$user->business_user) {
        		$client = IndividualClient::findIdentity($user->user_id);
        		$client->rewards = Billsource::countActivatedUsers();
        		$client->save(false);
        	}
            return $user->save();
        }
        return false;
    }
    
    /**
     * Sends an email with a link, for validating email.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
		$user = $this->user;
		
    	if ($user) {
    		return Yii::$app->mailer->compose([
    				'html' => 'activateEmail-html',
    				'text' => 'activateEmail-text'
    			],
    			[
    				'user' => $user,
    			])
    			->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
    			->setTo($user->email)
    			->setSubject('Billsource - Email Activated')
    			->send();
    	}
    	return false;
    }
}
