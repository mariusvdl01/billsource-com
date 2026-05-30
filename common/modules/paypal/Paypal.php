<?php

namespace common\modules\paypal;

use Yii;
use yii\db\Command;
use common\models\invoice\Invoice;
use common\helpers\ArrayHelper;
use common\models\payment\PaymentFees;

/**
 * Paypal module definition class
 */
class Paypal extends \yii\base\Module
{
    protected $paypalApi;
    protected $command;
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\paypal\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->paypalApi = Yii::$app->paypal;
        if(is_null($this->command)) {
            $this->command = new Command();
            $this->command->db = Yii::$app->db;
        }
    }

    public function getReturnUrl()
    {
        return Yii::$app->request->getHostInfo() . '/paypal/validation';
    }

    public function setTransactionDetails($post)
    {
        $this->paypalApi->setECRequestData($post);
    }
    
    public function doCreateExpressCheckoutPayment()
    {
        return $this->paypalApi->doExpressCheckout();
    }
    
    public function doExecuteExpressCheckoutPayment($paymentId, $payerId) 
    {
        return $this->paypalApi->doExecuteExpressCheckout($paymentId, $payerId);
    }

    public function processTransaction($response, $user)
    {
        $valid = false;
        $client = $user->identity;
        $clientType = 'IND';
        if($client->business_user)
            $clientType = 'BIZ';

        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $command = $this->command;
        $paid = $response->getState() == 'approved' ? '1' : '0';
        $orderNum = $response->getTransactions()[0]->getInvoiceNumber();
        $result = $this->validateResponse($response, $orderNum, $paid);
        $data = [
          'paid' => $paid,
          'response_time' => $now,
          'receipt_id' => $orderNum
        ];

        $command->update('receipt', $data, "receipt_id = $orderNum")->execute();
        if(isset($response) && isset($orderNum))
        {
            $data = [
              'payment_result' => $paid,
            ];
            $command->update('invoice_payment', $data, "pay_index = $orderNum")->execute();

            if($paid == '1')
            {
                $sql = 'UPDATE ' . Invoice::tableName() . ' SET `paid` = ' . Invoice::INVOICE_PAID .', `status_id` = ' . Invoice::STATUS_PAID . ' 
                        WHERE `invoice_id` IN  
                                            (SELECT `invoice_id` 
                                            FROM `invoice_payment` 
                                            WHERE `payment_result` = 1 
                                            AND `pay_index` = ' . $orderNum . ')';

                $command->setSql($sql)->execute();

                $sql = 'UPDATE `payment_fees` SET `fee_paid` = ' . PaymentFees::TRANX_FEE_PAID
                  .' WHERE `payment_index` = ' . $orderNum;
                $command->setSql($sql)->execute();

                $sql = "INSERT INTO `reward` (`reference_type`, `reference_id`, `description`, `amount`, `created_at`)
                        SELECT '$clientType', `a`.`user_id` , 'Payment rewards', FLOOR(SUM(`payment_amount`)) `inv_total`, CURRENT_TIMESTAMP
                        FROM `invoice_payment` `a` 
                        INNER JOIN `user` `u` ON `u`.`user_id` = `a`.`user_id` 
                        WHERE `payment_result` = 0 
                        AND `pay_index` = $orderNum
                        GROUP BY `payment_reference` LIMIT 0 , 1 ";
                $command->setSql($sql)->execute();
                // update rewards
                if(!$client->business_user) {
                    $sql = "SELECT SUM(`amount`) `rewards` 
                            FROM `reward` 
                            WHERE `reference_type` = '$clientType' 
                            AND `reference_id` = (SELECT `user_id` 
                                                    FROM `user` 
                                                    WHERE `user_id` = $client->user_id)";

                    $data = $command->setSql($sql)->queryAll();

                    if(!empty($data['rewards'])) {
                        $row = [];
                        ArrayHelper::recursive($data, $row);

                        $sql = 'UPDATE ';
                        if($clientType == 'IND')
                            $sql .= '`individual_client` ';
                        else
                            $sql .= '`business_client` ';

                        $sql .= 'SET `rewards` = ' . $row['rewards'] . ' 
                                WHERE `user_id` = ' . $client->user_id;
                        $command->setSql($sql)->execute();
                    }
                }
                $valid = true;
            }
        }
        return array('valid' => $valid, 'result' => $result);
    }

    protected function validateResponse($response, $orderNum)
    {
        $messages = '';

        if(!isset($response))
            $messages[] = 'Invalid Paypal response. No response posted.';
        if(!isset($orderNum))
            $messages[] = 'Invalid invoice number ID';

        return $messages;
    }
}
