<?php

namespace common\models\ecosystem;

use common\models\business\BusinessClient;
use common\models\business\BusinessClientCrm;
use common\models\invoice\Invoice as Bill;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%business_ecosystem}}".
 *
 * @property integer $id
 * @property integer $business_id
 * @property float $suppliers_total
 * @property float $buyers_total
 * @property float $consumers_total
 * @property float $ecosystem_total
 * @property string $growth_potential
 * @property integer $number_suppliers
 * @property integer $number_buyers
 * @property integer $number_consumers
 * @property integer $adjacent_ecosystem
 * @property integer $growth_factor
 * @property integer $ecosystem_health
 *
 * @property BusinessClient $business
 */
class BusinessEcosystem extends \common\models\BaseActiveRecord
{
    const GROWTH_POTENTIAL_FACTOR = 5;
    const ADJACENT_ECOSYSTEM_FACTOR = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_ecosystem}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'number_suppliers', 'number_buyers', 'number_consumers', 'adjacent_ecosystem', 'growth_factor', 'ecosystem_health'], 'integer'],
            [['suppliers_total', 'buyers_total', 'consumers_total', 'ecosystem_total', 'growth_potential'], 'number'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessClient::className(), 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    /**
     * Overrides the default behavior inherited from parent class
     *
     * @override
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'suppliers_total' => Yii::t('app', 'Suppliers Total'),
            'buyers_total' => Yii::t('app', 'Buyers Total'),
            'consumers_total' => Yii::t('app', 'Consumers Total'),
            'ecosystem_total' => Yii::t('app', 'Ecosystem Total'),
            'growth_potential' => Yii::t('app', 'Growth Potential'),
            'number_suppliers' => Yii::t('app', 'Number Suppliers'),
            'number_buyers' => Yii::t('app', 'Number Buyers'),
            'number_consumers' => Yii::t('app', 'Number Consumers'),
            'adjacent_ecosystem' => Yii::t('app', 'Adjacent Ecosystem'),
            'growth_factor' => Yii::t('app', 'Growth Factor'),
            'ecosystem_health' => Yii::t('app', 'Ecosystem Health'),
        ];
    }

    /**
     * Find all debtor (buyers) invoices
     *
     * @return array $buyers result of query
     */
    public static function findAllBillsForBuyers()
    {
        $query = "SELECT a.business_id, SUM(total) buyers_total
            FROM " . Bill::tableName() . " a
            INNER JOIN business_client b ON a.business_id = b.id
            INNER JOIN business_client c ON client_email = c.email
            INNER JOIN invoice_age_type ON age_paid = paid
            AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    			            AND DATEDIFF(NOW(), due_date) <= maximum_days))
    	    WHERE deleted =:deleted
    	    AND a.type =:type
    	    GROUP BY a.business_id";

        $buyers = parent::getDb()->createCommand($query, [
            ':deleted' => Bill::NOT_DELETED,
            ':type' => Bill::TYPE_INVOICE,
        ])->queryAll();

        return $buyers;
    }

    /**
     * Find all debtor (consumers) invoices
     *
     * @return array $consumers result of query
     */
    public static function findAllBillsForConsumers()
    {
        $query = "SELECT a.business_id, SUM(total) consumers_total
            FROM " . Bill::tableName() . " a
            INNER JOIN business_client b ON a.business_id = b.id
            INNER JOIN individual_client c ON client_email = c.email
            INNER JOIN invoice_age_type ON age_paid = paid
            AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    			            AND DATEDIFF(NOW(), due_date) <= maximum_days))
    	    WHERE deleted =:deleted
    	    AND a.type =:type
    	    GROUP BY a.business_id";

        $consumers = parent::getDb()->createCommand($query, [
            ':deleted' => Bill::NOT_DELETED,
            ':type' => Bill::TYPE_INVOICE,
        ])->queryAll();

        return $consumers;
    }

    /**
     * Find all supplier invoices
     *
     * @return array $suppliers result of query
     */
    public static function findAllBillsForSuppliers()
    {
        $query = "SELECT COUNT(DISTINCT a.business_id) number_suppliers, b.id, SUM(a.total) suppliers_total
    			FROM " . Bill::tableName() . " a
    			INNER JOIN business_client b ON client_email = b.email
    			INNER JOIN business_client c ON a.business_id = c.id
    			INNER JOIN invoice_age_type ON age_paid = paid
    				AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    				AND DATEDIFF(NOW(), due_date) <= maximum_days))
    			WHERE deleted =:deleted
    			AND a.type =:type
    			AND client_email IN
    							(SELECT bc.email FROM user u
    							INNER JOIN business_client bc ON u.id = bc.user_id
    							WHERE business_user = 1)
    			GROUP BY b.id";

        $suppliers = parent::getDb()->createCommand($query, [
            ':deleted' => Bill::NOT_DELETED,
            ':type' => Bill::TYPE_INVOICE,
        ])->queryAll();

        return $suppliers;
    }

    public static function findAdjacentEcosystems()
    {
        $customers = (new Query())->select('COUNT(id) customers, business_id')
            ->from(BusinessClientCrm::tableName())
            ->groupBy('business_id')
            ->all();

        return $customers;
    }

    public static function findNumberOfBuyers()
    {
        $buyers = (new Query())->select('COUNT(id) number_buyers, business_id')
            ->from(BusinessClientCrm::tableName())
            ->where(['is_business' => true])
            ->groupBy('business_id')
            ->all();

        return $buyers;
    }

    public static function findNumberOfConsumers()
    {
        $consumers = (new Query())->select('COUNT(id) number_consumers, business_id')
            ->from(BusinessClientCrm::tableName())
            ->where(['is_business' => false])
            ->groupBy('business_id')
            ->all();

        return $consumers;
    }


    /**
     * @return string formatted suppliers total
     */
    public function getSuppliersTotal()
    {
        return Yii::$app->formatter->asCurrency($this->suppliers_total);
    }

    /**
     * @return string formatted buyers total
     */
    public function getBuyersTotal()
    {
        return Yii::$app->formatter->asCurrency($this->buyers_total);
    }

    /**
     * @return string formatted consumers total
     */
    public function getConsumersTotal()
    {
        return Yii::$app->formatter->asCurrency($this->consumers_total);
    }

    /**
     * @return string formatted ecosystem total
     */
    public function getEcosystemTotal()
    {
        return Yii::$app->formatter->asCurrency($this->ecosystem_total);
    }

    /**
     * @return string formatted growth potential total
     */
    public function getGrowthPotential()
    {
        return Yii::$app->formatter->asCurrency($this->growth_potential);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessClient::className(), ['id' => 'business_id']);
    }
}
