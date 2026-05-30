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
use yii\queue\JobInterface;
use yii\queue\Queue;

class WithdrawRewardsRequestJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var float
     */
    public $gasFee;

    /**
     * @var string
     */
    public $country;

    /**
     * @var
     */
    public $walletAddress;

    /**
     * @var float
     */
    public $transactionFee;

    /**
     * @param Queue $queue
     */
    public function execute($queue)
    {
        Yii::$app->mailer->sendEmailWithTemplate(
            [Yii::$app->params['infoEmail'] => Yii::$app->name],
            $this->email,
            'Billsource - Withdraw Token',
            ['html' => 'tokenWithdrawalRequest-html', 'text' => 'tokenWithdrawalRequest-text'],
            [
                'email' => $this->email,
                'amount' => $this->amount,
                'gasFee' => $this->gasFee,
                'country' => $this->country,
                'walletAddress' => $this->walletAddress,
                'transactionFee' => $this->transactionFee,
            ],
            Yii::$app
        );
    }
}