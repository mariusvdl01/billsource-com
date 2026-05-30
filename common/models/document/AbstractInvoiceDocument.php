<?php

namespace common\models\document;

use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\individual\IndividualClient;
use common\models\invoice\InvoicePayment;
use common\models\Receipt;
use common\models\User;
use yii\db\ActiveRecord;

abstract class AbstractInvoiceDocument extends AbstractDocument
{
    protected static $months = [
        'January', 'February', 'March',
        'April', 'May', 'June',
        'July', 'August', 'September',
        'October', 'November', 'December'
    ];

    /**
     * AbstractInvoiceDocument constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Get months of the year
     *
     * @return array
     */
    public static function getMonths()
    {
        return static::$months;
    }

    /**
     *
     *
     * @return string
     */
    public static function findBusinessBillsByCreditor() {
        $query = '(SELECT inv.id, trading_name , reference_number , amount, inv.read,
    			discount, paid, comments , due_date, minimum_days, description, image, total
    			FROM ' . parent::tableName() . ' inv 
    			INNER JOIN business_client bc ON inv.business_id = bc.id  
    			INNER JOIN invoice_age_type ON age_paid = paid 
    				AND (age_paid = 1 or (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days 
    				AND DATEDIFF(NOW(), due_date) <= maximum_days))  
    			LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `a` ON a.invoice_id = inv.id 
    			WHERE deleted =:deleted
    			AND inv.type =:type 
    			AND inv.paid =:paid
    			AND client_email IN 
    							(SELECT bc.email FROM user u 
    							INNER JOIN business_client bc ON u.id = bc.user_id  
    							WHERE business_user = 1 
    							AND u.id =:userId)
    			) 
    			
    			UNION 
    			
    			(SELECT inv.id, trading_name, reference_number, amount, inv.read,
    			discount, paid, comments, due_date, minimum_days, description, image, total
    			FROM ' . parent::tableName() . ' inv 
    			INNER JOIN business_client bc ON inv.business_id = bc.id  
    			INNER JOIN invoice_age_type ON DATEDIFF(NOW(), due_date) >= minimum_days 
                AND DATEDIFF(NOW(), due_date) <= maximum_days 
    			LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `a` ON a.invoice_id = inv.id 
    			WHERE deleted =:deleted 
    			AND inv.type =:type 
    			AND inv.paid =:paid
    			AND client_id IN ( 
        							(SELECT id_number FROM user u 
        							INNER JOIN business_client bc on u.id = bc.user_id
        							WHERE business_user = 1 
        							AND u.id =:userId)
                                    OR 
                                    (SELECT registration_number FROM user u 
                                    INNER JOIN business_client bc on u.id = bc.user_id
                                    WHERE business_user = 1 
                                    AND u.id =:userId)
                                )
    			)';

        return $query;
    }

    /**
     * Get full name of biller for PDF generation
     * @param string $businessId Business client Id
     * @return string
     */
    public function getBillerName($businessId = '')
    {
        $id = !empty($businessId) ? $businessId : $this->business_id;

        if (strcmp('0', $id) == 0) {
            return 'Self captured';
        }

        return BusinessClient::findOne(['id' => $id])->trading_name;
    }

    /**
     * Get full name of customer (debtor) for PDF generation
     * @return string
     */
    public function getCustomerName()
    {
        $customer = BusinessClientCrm::findOne(['email' => $this->client_email]);
        if($customer->is_business)
            return $customer->trading_name ? $customer->trading_name : $customer->registered_name;
        else
            return $customer->first_name . ' ' . $customer->last_name;
    }

    /**
     * Get invoice payment details
     * @return ActiveRecord
     */
    public function getPaymentDetails()
    {
        $payment = new InvoicePayment;

        return $payment->getData($this->id);
    }

    /**
     * Get invoice aging details
     * @return array|false
     */
    public function getAgeTypeDetails()
    {
        $customer = BusinessClientCrm::find()
            ->where('[[email]]=:email', [
                ':email' => $this->client_email
            ])
            ->one();
        $feeType = 'business_fee';
        if(!$customer->is_business) {
            $feeType = 'invoice_fee';
        }

        $query = "SELECT minimum_days, description, $feeType AS fee
            FROM " . self::tableName() . " a
            INNER JOIN invoice_age_type ON age_paid = 0
            AND (DATEDIFF(NOW(), due_date) >= minimum_days
            AND DATEDIFF(NOW(), due_date) <= maximum_days)
            WHERE a.id =:id";
        $result = self::getDb()->createCommand()
            ->setSql($query)
            ->bindValue(':id', $this->id)
            ->queryOne();

        return $result;
    }

    /**
     * Get dashboard charts
     * @param BusinessClient $user client instance object
     * @return array data for dashboard charts
     */
    public function getDataForCharts(BusinessClient $user)
    {
        $db = parent::getDb();
        $deleted = parent::NOT_DELETED;
        $type = parent::TYPE_INVOICE;
        $debtorsData = $creditorsData = $customersData = $customersBarChartData = array();
        $userId = $user->user_id;
        $debtorsQuery = parent::findAllBillsForDebtors();
        $creditorsQuery = self::findAllBillsByCreditor();

        $debtors = $db->createCommand($debtorsQuery, [
            ':deleted' => $deleted,
            ':type'     => $type,
            ':userId'   => $userId
        ])->queryAll();

        $creditors = $db->createCommand($creditorsQuery, [
            ':deleted'  => $deleted,
            ':type'     => $type,
            ':userId'   => $userId
        ])->queryAll();

        if ($debtors) {
            $debtorsData = $this->getAggregatedPieChartData($debtors);
            $customersData = $this->getPieChartDataPerCustomer($user, $debtors);
            $customersBarChartData = $this->getMonthlyAggregatedBarChartData($user, $debtors);
        }

        if ($creditors) {
            $creditorsData = $this->getAggregatedPieChartData($creditors);
        }

        return [
            'debtorsData' => $debtorsData,
            'creditorsData' => $creditorsData,
            'customersData' => $customersData,
            'customersBarChartData' => $customersBarChartData
        ];
    }

    /**
     * @return string
     */
    public static function findAllBillsByCreditor()
    {
        $query = '(SELECT a.id, inv.id, trading_name , reference_number , amount,
    			discount, paid, comments , due_date, minimum_days, description, image, total
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			INNER JOIN invoice_age_type ON age_paid = paid
    				AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    				AND DATEDIFF(NOW(), due_date) <= maximum_days))
    			LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `a`
    			ON a.invoice_id = inv.id
    			WHERE deleted =:deleted
    			AND inv.type =:type
    			AND client_email IN
    							(SELECT bc.email FROM user u
    							INNER JOIN business_client bc ON u.id = bc.user_id
    							WHERE business_user = 1
    							AND u.id =:userId)
    			)

    			UNION

    			(SELECT a.id, inv.id, trading_name, reference_number, amount,
    			discount, paid, comments, due_date, minimum_days, description, image, total
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			INNER JOIN invoice_age_type ON DATEDIFF(NOW(), due_date) >= minimum_days
                AND DATEDIFF(NOW(), due_date) <= maximum_days
    			LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `a`
    			ON a.invoice_id = inv.id
    			WHERE deleted =:deleted
    			AND inv.type =:type
    			AND client_id IN (
        							(SELECT id_number FROM user u
        							INNER JOIN business_client bc on u.id = bc.user_id
        							WHERE business_user = 1
        							AND u.id =:userId)
                                    OR
                                    (SELECT registration_number FROM user u
                                    INNER JOIN business_client bc on u.id = bc.user_id
                                    WHERE business_user = 1
                                    AND u.id =:userId)
                                )
    			)';

        return $query;
    }

    protected function getAggregatedPieChartData($chartsData)
    {
        $data['total'] = array();
        if(is_array($chartsData)) {
            $data['total']['grand'] = 0;
            foreach($chartsData as $value) {
                if(!isset($data['total'][$value['minimum_days']]))
                    $data['total'][$value['minimum_days']] = 0;

                switch($value['minimum_days']) {
                    case '31':
                    case '61':
                    case '91':
                    case '121':
                    case '0':
                        $data['total'][$value['minimum_days']] += $value['total'];
                        break;
                    default:
                        $data['total'][$value['minimum_days']] += $value['total'];

                }
                $data['total']['grand'] += $value['total'];
            }
        }

        return $data;
    }

    protected function getPieChartDataPerCustomer($client, $chartsData)
    {
        $customers = (new BusinessClientCrm())->findCustomersForClient($client->id);
        $data['total'] = array();

        if(is_array($chartsData)) {
            $data['total']['grand'] = 0;
            foreach($chartsData as $value) {
                if(in_array($value['client_email'], $customers)) {
                    if(!isset($data['total'][$value['client_email']])) {
                        $data['total'][$value['client_email']] = 0;
                    }

                    $data['total'][$value['client_email']] += $value['total'];
                }
                $data['total']['grand'] += $value['total'];
            }
        }

        return $data;
    }

    protected function getMonthlyAggregatedBarChartData($client, $chartsData)
    {
        $customers = (new BusinessClientCrm())->findCustomersForClient($client->id);
        $data = array();

        // calculate monthly total per customer
        if(is_array($chartsData)) {
            foreach($chartsData as $value) {
                if(in_array($value['client_email'], $customers)) {
                    $month = (new \DateTime($value['issue_date']))->format('n');
                    if (!isset($data[$value['alt_business_name']][$month])) {
                        $data[$value['alt_business_name']][$month] = 0;
                    }

                    $data[$value['alt_business_name']][$month] += $value['total'];
                }
            }
        }

        return $data;
    }

    /**
     * Save payment information of online payments
     */
    public function savePaymentInfo()
    {
        $receipt = new Receipt();
        $payment = new InvoicePayment();
        $receipt->paid = 1;
        $receipt->response_time = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $user = User::findOne(['email' => $this->client_email]);

        if ($user->business_user) {
            $client = BusinessClient::findOne(['user_id' => $user->id]);
        } else {
            $client = IndividualClient::findOne(['user_id' => $user->id]);
        }

        if ($receipt->save(false)) {
            $receipt->refresh();
            $id = $receipt->getPrimaryKey();
            $payment->pay_index = $id;
            $payment->invoice_id = $this->id;
            $payment->payment_reference = $this->reference_number;
            $payment->payment_amount = $this->total;
            $payment->user_id = $client->user_id;
            $payment->payment_result = 0;
            $payment->save(false);
		}
    }
}