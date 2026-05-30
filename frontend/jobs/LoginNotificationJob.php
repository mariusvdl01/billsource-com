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

class LoginNotificationJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $email;

    /**
     * @param Queue $queue
     */
    public function execute($queue)
    {
        $user = User::findByEmail($this->email);

        Yii::$app->mailer->compose([
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
}
