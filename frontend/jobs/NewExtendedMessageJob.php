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

use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\JobInterface;

class NewExtendedMessageJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $from;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $viewPath;

    /**
     * @param Queue $queue
     */
    public function execute($queue)
    {
        Yii::$app->mailer->viewPath = $this->viewPath;

        Yii::$app->mailer->compose(['html' => 'message', 'text' => 'text/message'], ['content' => $this->message])
            ->setTo($this->email)
            ->setFrom($this->from)
            ->setSubject($this->title)
            ->send();
    }
}
