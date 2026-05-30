<?php

namespace common\helpers;

use common\models\bill\UserBillRequest;
use common\models\business\BusinessClient;
use common\models\individual\IndividualClient;
use common\models\invoice\Invoice;
use common\models\invoice\InvoiceLineManager;
use common\models\invoice\InvoicePayment;
use common\models\payment\PaymentFees;
use common\models\Receipt;
use common\models\User;
use DateTime;
use Yii;
use yii\db\Command;

class Billsource extends \yii\base\Model
{
    protected static $comm = null;

    public static function findUserBillRequestsByType($user_id)
    {
        $user = User::findOne(['id' => $user_id]);
        $type = 1;

        if (!$user->business_user) {
            $type = 2;
        }

        return UserBillRequest::findAllBillRequestByType($user_id, $type);
    }

    public static function countActivatedUsers()
    {
        $points = IndividualClient::REWARD_POINTS;
        $comm = self::getActiveCommand();
        $sql = "SELECT COUNT(`user_id`) AS `count_activated` FROM `user`
    			WHERE `is_activated` = 1 AND business_user = 0";
        $rows = $comm->setSql($sql)->queryAll();
        $rows = $rows[0];

        if ($rows && count($rows) > 0 && $rows['count_activated'] <= 2500)
            $points = 150;

        return $points;
    }

    protected static function getActiveCommand()
    {
        if (is_null(self::$comm)) {
            self::$comm = new Command;
            self::$comm->db = Yii::$app->db;
        }

        return self::$comm;
    }

    public static function transformImage($uploadedImage, $imagePath, $source, $rand)
    {
        $image = new Image;
        $image->source_path = $source;
        $ext = end(explode('.', $uploadedImage->name));

        // indicate a target image
        $image->target_path = $imagePath . DIRECTORY_SEPARATOR . $rand . DIRECTORY_SEPARATOR
            . Yii::$app->security->generateRandomString(8) . ".{$ext}";

        // resize
        // and if there is an error, show the error message
        if (!$image->resize(220, 75, Image::IMAGE_BOXED, -1))

            // apply some filters
            // (this combination produces the "sepia" filter)
            $image->apply_filter(array(
                array('grayscale'),
                array('colorize', 90, 60, 40),
            ));
        unlink($source);
        return $image;
    }

    public static function findSmsToSend()
    {
        $sql = 'SELECT DISTINCT DATE_FORMAT(CURRENT_DATE(), \'%Y%m\') `date`,
        				IFNULL(`b`.`mobile`, IFNULL(`c`.`phone_number`, `a`.`client_mobile`)) `mobile`,
        				IFNULL(`b`.`first_name`, IFNULL(`c`.`contact_person`, \'Debtor\')) `debtor`
        		FROM ' . Invoice::tableName() . ' `a` 
				LEFT JOIN `individual_client` `b` ON (`a`.`client_email` = `b`.`email` 
												  OR `a`.`client_mobile` = `b`.`mobile`) 
				LEFT JOIN  `business_client` `c` ON (`a`.`client_id` = `c`.`registration_number` 
												 OR `a`.`alt_business_name` = `c`.`trading_name` 
												 OR `a`.`client_email` = `c`.`email` 
												 OR `a`.`client_mobile` =  `c`.`phone_number`)
        		INNER JOIN `business_client` `d` ON `d`.`id` = `a`.`business_id`
        		INNER JOIN `business_profile` `e` ON `e`.`id` = `d`.`profile_id`
        		INNER JOIN `company` `f` on 1 = 1
        		LEFT JOIN (SELECT * FROM `sms_log` WHERE `period` = DATE_FORMAT(CURRENT_DATE(), \'%Y%m\')) `g`
        		ON `d`.`id` = `g`.`business_id` 
				WHERE `a`.`paid` = 0 
				AND `a`.`deleted` = 0
				OR (IFNULL(`d`.`maximum_limit_sms`, `e`.`maximum_limit_sms`) > IFNULL(`count`, 0))
				ORDER BY `date` ASC';

        $data = self::getActiveCommand()->setSql($sql)->queryAll();

        return $data === false ? '' : $data;
    }

    public static function findEmailToSend()
    {
        $sql = 'SELECT DISTINCT DATE_FORMAT(CURRENT_DATE(), \'%Y%m\') `date`,
						IFNULL(`b`.`email`, IFNULL(`c`.`email`, `a`.`client_email`)) `email`
				FROM ' . Invoice::tableName() . ' `a`
				LEFT JOIN `individual_client` `b` ON (`a`.`client_email` = `b`.`email`
												  OR `a`.`client_mobile` = `b`.`mobile`)
				LEFT JOIN  `business_client` `c` ON (`a`.`client_id` = `c`.`registration_number`
												 OR `a`.`alt_business_name` = `c`.`trading_name`
												 OR `a`.`client_email` = `c`.`email`
												 OR `a`.`client_mobile` =  `c`.`phone_number`)
				INNER JOIN `business_client` `d` ON `d`.`id` = `a`.`business_id`
				INNER JOIN `business_profile` `e` ON `e`.`id` = `d`.`profile_id`
				INNER JOIN `company` `f` ON 1 = 1
				WHERE `a`.`paid` = 0
				AND `a`.`deleted` = 0
				ORDER BY `date` ASC';

        $data = self::getActiveCommand()->setSql($sql)->queryAll();

        return $data === false ? [] : $data;
    }

    public static function getReferenceNumber($controller_id = 'invoice', $prefix = 'BILSRN-')
    {
        if (stripos($controller_id, 'payroll')) {
            $prefix = 'BILSRN-';
        } elseif (stripos($controller_id, 'quote')) {
            $prefix = 'BILSRN-';
        } elseif (stripos($controller_id, 'payslip')) {
            $prefix = 'BILSRN-';
        } elseif (stripos($controller_id, 'ticket')) {
            $prefix = 'BILSRN-';
        }

        $chars = '0123456789';

        srand(self::make_seed());

        $i = 0;
        $reference = '';

        while ($i < 8) {
            $num = rand() % 12;
            $tmp = substr($chars, $num, 1);
            $reference .= $tmp;
            $i++;
        }

        return $prefix . $reference;
    }

    private static function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());

        return (float)$sec + ((float)$usec * 100000);
    }

    public function loadInvoice($invoice_id)
    {
        $invoice = Invoice::find()
            //->joinWith('invoiceAgeType', true, 'INNER JOIN')
            ->where('[[id]]=:id', [':id'=>$invoice_id])
            //->andWhere('age_paid = 0
            //    AND DATEDIFF(NOW(), due_date) >= minimum_days
            //    AND DATEDIFF(NOW(), due_date) <= maximum_days')
            ->one();
        $lineManager = new InvoiceLineManager($invoice);

        $biller = BusinessClient::find()
            ->joinWith('province', true, 'INNER JOIN')
            ->where('[[business_client.id]]=:id', [':id'=>$invoice->business_id])
            ->one();

        $invoice->updateAttributes(['read' => 1]);

        return ['invoice' => $invoice, 'lines' => $lineManager, 'biller' => $biller];
    }

    public function loadPaidInvoice($invoice_id)
    {
        $invoice = Invoice::find()->with([
                'paymentFees',
                'invoicePayments'
            ])
            ->where('[[document.id]]=:id', [':id'=>$invoice_id])
            ->andWhere('[[paid]]=:paid', [':paid' => 1])
            ->one();

        $biller = BusinessClient::find()
            ->joinWith('province', true, 'INNER JOIN')
            ->where('[[business_client.id]]=:id', [':id'=>$invoice->business_id])
            ->one();

        return ['invoice' => $invoice, 'lines' => [], 'biller' => $biller];
    }

    public function loadCreditorStatement($user)
    {
        $creditor = null;
        $biller = null;
        $invoices = Invoice::find()
            ->where('[[type]]=:type', [':type' => 'INV'])
            ->andWhere('[[client_email]]=:email', [':email' => $user->email])
            ->andWhere(['not', ['business_id' => 0]])
            ->orderBy(['due_date' => SORT_ASC])
            ->all();
        if($user->business_user) {
            $creditor = BusinessClient::find()
                ->joinWith('province', true, 'INNER JOIN')
                ->where('[[user_id]]=:id', [':id' => $user->id])
                ->one();
        } else {
            $creditor = IndividualClient::find()
                ->joinWith('province', true, 'INNER JOIN')
                ->where('[[user_id]]=:id', [':id' => $user->id])
                ->one();
        }

        if(count($invoices)) {
            foreach($invoices as $invoice) {
                if(!$biller) {
                    $biller = BusinessClient::find()
                        ->joinWith('province p', true, 'INNER JOIN')
                        ->where('[[business_client.id]]=:id', [':id' => $invoice->business_id])
                        ->one();
                }
            }
        }

        return [
            'creditor' => $creditor,
            'biller' => $biller,
            'invoices' => $invoices,
        ];
    }

    public function loadDebtorStatement($user)
    {
        $biller = null;
        $invoices = Invoice::find()
                    ->where('[[type]]=:type', [':type' => 'INV'])
                    ->andWhere('[[business_id]]=:id', [':id' => $user->id])
                    ->orderBy(['due_date' => SORT_ASC])
            ->all();
        return [
            'biller' => $user,
            'invoices' => $invoices,
        ];
    }

    public function startInvoicePaymentProcess($user_id, $ids, $records, &$index)
    {
        $index = 0;
        $result = array();
        $feeType = 'business_fee';
        $receipt = new Receipt();
        $receipt->paid = 0;
        $comm = self::getActiveCommand();
        $biz = User::findOne($user_id)->business_user;

        if (!$biz)
            $feeType = '`invoice_fee`';

        if ($receipt->save(false)) {
            $receipt->refresh();
            $index = $receipt->getPrimaryKey();

            foreach ($records as $record) {

                $payment = new InvoicePayment;
                $payment->pay_index = $index;
                $payment->invoice_id = $record['id'];
                $payment->payment_reference = $record['reference_number'];
                $payment->payment_amount = $record['total'];
                $payment->user_id = $user_id;
                $payment->save(false);
            }

            $amount = PaymentFees::TRANX_FEE;
            $paymentFee = new PaymentFees;
            $paymentFee->payment_index = $index;
            $paymentFee->reference = PaymentFees::TRANX_FEE_REF;
            $paymentFee->amount = $amount;
            $paymentFee->fee_paid = PaymentFees::TRANX_FEE_UNPAID;
            $now = new DateTime();
            if ($paymentFee->save(false)) {
                $query = "INSERT INTO `payment_fees` (`payment_index`, `reference`,
                    `amount`, `fee_paid`, `created_at`) (SELECT $index, `invoice_reference`,
                    (COUNT(`a`.`id`) * $feeType), 0, '" . $now->format('Y-m-d H:i:s') . "'
                    FROM " . Invoice::tableName() . " `a`
                    INNER JOIN `invoice_age_type` ON DATEDIFF(NOW(), `due_date`) >= `minimum_days`
                    AND DATEDIFF(NOW(), `due_date`) <= `maximum_days` 
                    WHERE `type` = :type
                    AND `paid` = 0
                    AND (`business_fee` <> 0 OR `invoice_fee` <> 0)
                    AND `a`.`id` IN (0";

                    foreach ($ids as $id) {
                        $query .= ',' . $id;
                    } $query .= ') GROUP BY description, invoice_reference, ' . $feeType . ')';

                    $comm->setSql($query)
                        ->bindValue(':type','INV');

                if ($comm->execute() !== false) {
                    $sql = 'SELECT `id` AS `invoice_id`, \'Billsource\' AS `alt_business_name`,
                        `reference` AS `reference_number`, `amount` AS `amount`, 0 AS `discount`,
                        0 AS `paid`, \'Billsource fee\' AS `comments`, `created_at` AS `due_date`,
                        0 AS `client_id`
                        FROM `payment_fees` 
                        WHERE `fee_paid` = 0 AND `payment_index` =:index';
                    $result = $comm->setSql($sql)->bindValue(':index', $index)->queryAll();
                }
            }
        }

        return $result;
    }

    function getCompanyRootDirectory()
    {
        return $this->getCompanyDirectory();
    }

    function getCompanyDirectory($field = '')
    {
        $directory = '';
        $fields = '`root_dir`';

        if (isset($field) && $field != '')
            $fields .= ', ' . $field;

        $sql = 'SELECT CONCAT(' . $fields . ')  `directory` FROM `company` LIMIT 0, 1';

        if ($row = self::getActiveCommand()->setSql($sql)->queryOne())
            $directory = $row['directory'];

        return $directory;
    }

    function getCompanyVaultDirectory()
    {
        return $this->getCompanyDirectory('`vault_dir`');
    }
}
