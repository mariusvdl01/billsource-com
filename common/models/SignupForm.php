<?php
namespace common\models;

use common\models\business\BusinessClient;
use common\models\individual\IndividualClient;
use frontend\jobs\SignupNotificationJob;
use kekaadrenalin\recaptcha3\ReCaptchaValidator;
use Yii;
use yii\base\ErrorException;
use yii\base\Model;

/**
 * User registration/signup form
 *
 */
class SignupForm extends Model
{
    const CATEGORY_PERSONAL = '3';

    /**
     * Guest user first name
     *
     * @var string $firstname user First name
     */
    public $firstname;

    /**
     * Guest user last name
     *
     * @var string $lastname user Last name
     */
    public $lastname;

    /**
     * Guest user email
     *
     * @var string $email user email
     */
    public $email;

    /**
     * Guest user password
     *
     * @var string $password user password
     */
    public $password;

    /**
     * Guest user confirm password
     *
     * @var string $confirmPassword password repeat
     */
    public $confirmPassword;

    /**
     * Guest user category
     *
     * @var string $category type of user
     */
    public $category;

    /**
     * Guest user terms and conditions
     *
     * @var boolean $terms has accepted terms and condition
     */
    public $tcs = false;
    /**
     * Verify code to prevent spambots submitting spam emails
     *
     * @var string $reCaptcha verification code
     */
    public $reCaptcha;

    /**
     * Validation rules to apply to class properties
     *
     * @return array an array of validation rules
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'email', 'password', 'confirmPassword'], 'required', 'message' => 'Field required'],
            [['firstname', 'lastname', 'email'], 'filter', 'filter' => 'trim'],
            [['firstname', 'lastname'], 'string', 'min' => 3, 'max' => 255],

            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'common\models\User', 'message' => 'This email address has already been taken.'],

            [['password', 'confirmPassword'], 'string', 'min' => 6],

            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Password does not match'],

            ['category', 'required', 'message' => 'Please choose a category'],

            ['tcs', 'required', 'requiredValue' => 1, 'message' => 'Please accept Billsource Terms & Conditions.'],

            [['reCaptcha'], ReCaptchaValidator::class, 'acceptance_score' => 0.9]
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $result = false;

        $user = Yii::createObject('common\models\User');
        $user->setEmail($this->email);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->setStatus();
        $user->generateUsername();
        $category = $this->category;
        if ($category != self::CATEGORY_PERSONAL)
            $user->setBusinessUser();

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            if ($user->save() && $user->refresh()) {
                if ($category == self::CATEGORY_PERSONAL) {
                    $client = new IndividualClient();
                    $result = $client->signupIndividualUser($user, $this);
                } else {
                    $client = new BusinessClient();
                    $result = $client->signupBusinessUser($user, $this);
                }

                if ($result) {
                    $transaction->commit();
                    return $user;
                }
            }
        } catch (ErrorException $e) {
            $transaction->rollback();
        }

        return $result;
    }

    /**
     * Sends an email with a link, for validating email.
     */
    public function sendEmail()
    {
        Yii::$app->queue->push(new SignupNotificationJob(
            [
                'email' => $this->email,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname
            ]
        ));
    }
}
