<?php
/**
 * Copyright &copy; 2021 Billsource. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @category Netcraft
 * @package billsource
 * @copyright Copyright &copy; 2021 Billsource
 * @author Kenneth Onah <onah.kenneth@gmail.com>
 */

namespace frontend\jobs;

use common\models\User;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\JobInterface;

class SignupNotificationJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $firstname;
    /**
     * @var string
     */
    public $lastname;

    /**
     * @param Queue $queue
     */
    public function execute($queue)
    {
        $user = User::findByEmail($this->email);

        if ($user) {
            Yii::$app->mailer->compose([
                'html' => 'signupWelcome-html',
                'text' => 'signupWelcome-text'
            ],
                [
                    'email' => $user->email,
                    'authKey' => $user->auth_key,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname
                ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($this->email)
                ->setSubject('Billsource - Welcome Email')
                ->send();
        }
    }
}
