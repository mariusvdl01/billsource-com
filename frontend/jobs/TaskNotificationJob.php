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

class TaskNotificationJob extends BaseObject implements JobInterface
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
     * @var int
     */
    public $userId;

    /**
     * @var string
     */
    public $refNo;
    

    /**
     * @param Queue $queue
     */
    public function execute($queue)
    {
        $user = User::findById($this->userId);

        if ($user) {
            Yii::$app->mailer->compose([
                'html' => 'newTask-html',
                'text' => 'newTask-text'
            ],
                [
                    'email' => $user->email,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'refno' => $this->refNo
                ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($this->email)
                ->setSubject('Billsource - New Task Email')
                ->send();
        }
    }
}
