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

use common\models\business\BusinessClient;
use common\models\individual\IndividualClient;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\JobInterface;
use yii\web\NotFoundHttpException;

class SendLoanRequestJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $userId;
    /**
     * @var string
     */
    public $type;

    /**
     * @param Queue $queue
     * @throws NotFoundHttpException
     */
    public function execute($queue)
    {
        $emails = [
            'carina@cilreyn.co.za',
            'billsource.service@gmail.com',
        ];

        Yii::$app->mailer->compose([
            'html' => 'requestAssistance-html',
            'text' => 'requestAssistance-text'
        ],
            [
                'user' => $this->findClient(),
                'type' => $this->type,
            ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($emails)
            ->setSubject($this->type == 'loan' ? 'Billsource - Loan request' : 'Billsource - Debt counseling')
            ->send();
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findClient()
    {
        $userId = $this->userId;

        $model = IndividualClient::find()
            ->where('[[user_id]]=:user_id', ['user_id' => $userId])
            ->joinWith('province', true, 'INNER JOIN')
            ->one();

        if ($model === null) {
            $model = BusinessClient::find()
                ->where('[[user_id]]=:user_id', ['user_id' => $userId])
                ->joinWith('province', true, 'INNER JOIN')
                ->one();
        }

        if ($model === null) {
            throw new NotFoundHttpException('The user does not exist.');
        }

        return $model;
    }
}
