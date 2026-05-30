<?php

namespace common\models;

use common\models\business\BusinessClient;
use Yii;

/**
 * This is the model class for table "{{%debit_order}}".
 *
 * @property integer $id
 * @property string $reference_type
 * @property integer $reference_id
 * @property string $order_date
 * @property string $order_bank
 * @property string $order_bank_branch
 * @property string $order_branch_code
 * @property string $order_bank_account
 * @property double $order_amount
 * @property string $created_at
 *
 * @property BusinessClient $reference
 */
class DebitOrder extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%debit_order}}';
    }

    public static function updateDebitOrdersList()
    {
        $command = self::getDb()->createCommand();

        $sql = 'INSERT INTO `debit_order` (`reference_type`, `reference_id`, `order_date`, `order_bank`,
				`order_bank_branch`, `order_branch_code`,`order_bank_account`, `order_amount`, `created_at`)
				
				(SELECT \'BUS\', `a`.`id`, STR_TO_DATE(CONCAT(YEAR(CURRENT_DATE()), \':\', MONTH(CURRENT_DATE()),
				\':\', (CASE `debit_order_day` WHEN 0 THEN 1 ELSE `debit_order_day` END)), \'%Y:%c:%e\'), `debit_order_bank`, `debit_order_branch`,
				`debit_order_branch_code`, `debit_order_account`, `fee`, CURRENT_TIMESTAMP
				FROM `business_client` `a`
				INNER JOIN `business_profile` `b` ON `a`.`profile_id` = `b`.`id`
				LEFT JOIN `debit_order` `c` ON `reference_type` = \'BUS\' 
				AND `reference_id` = `a`.`id` 
				AND YEAR(CURRENT_DATE()) = YEAR(`order_date`) 
				AND MONTH(CURRENT_DATE()) = MONTH(`order_date`)
				WHERE NOT `a`.`profile_id` IN (3, 4) AND `c`.`id` IS NULL 
				AND CURRENT_DATE() <= STR_TO_DATE(CONCAT(YEAR(CURRENT_DATE()), \':\', MONTH(CURRENT_DATE()),
				\':\', (CASE `debit_order_day` WHEN 0 THEN 1 ELSE `debit_order_day` END)), \'%Y:%c:%e\'))
		
				UNION 
		
				(SELECT \'BUS\', `a`.`id`, STR_TO_DATE(CONCAT(YEAR(CURRENT_DATE()), \':\', MONTH(CURRENT_DATE()) + 1,
				\':\', (CASE `debit_order_day` WHEN 0 THEN 1 ELSE `debit_order_day` END)), \'%Y:%c:%e\'), `debit_order_bank`, `debit_order_branch`,
				`debit_order_branch_code`, `debit_order_account`, `fee`, CURRENT_TIMESTAMP
				FROM `business_client` `a`
				INNER JOIN `business_profile` `b` ON `a`.`profile_id` = `b`.`id`
				LEFT JOIN `debit_order` `c` ON `reference_type` = \'BUS\' AND `reference_id` = `a`.`id` 
				AND YEAR(CURRENT_DATE()) = YEAR(`order_date`) AND MONTH(CURRENT_DATE()) + 1 = MONTH(`order_date`) 
				WHERE NOT `a`.`profile_id` IN (3, 4) AND `c`.`id` IS NULL 
				AND CURRENT_DATE > STR_TO_DATE(CONCAT(YEAR(CURRENT_DATE()), \':\', MONTH(CURRENT_DATE()),
				\':\', (CASE `debit_order_day` WHEN 0 THEN 1 ELSE `debit_order_day` END)), \'%Y:%c:%e\'))';

        $command->setSql($sql)->execute();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference_type', 'reference_id', 'order_date', 'order_bank', 'order_bank_branch', 'order_branch_code',
                'order_bank_account', 'order_amount'], 'required'],
            [['reference_id'], 'integer'],
            [['order_date', 'created_at'], 'safe'],
            [['order_amount'], 'number'],
            [['reference_type', 'order_bank', 'order_bank_branch', 'order_branch_code', 'order_bank_account'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                 => Yii::t('app', 'Debit Order ID'),
            'reference_type'     => Yii::t('app', 'Reference Type'),
            'reference_id'       => Yii::t('app', 'Reference ID'),
            'order_date'         => Yii::t('app', 'Order Date'),
            'order_bank'         => Yii::t('app', 'Order Bank'),
            'order_bank_branch'  => Yii::t('app', 'Order Bank Branch'),
            'order_branch_code'  => Yii::t('app', 'Order Branch Code'),
            'order_bank_account' => Yii::t('app', 'Order Bank Account'),
            'order_amount'       => Yii::t('app', 'Order Amount'),
            'created_at'         => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReference()
    {
        return $this->hasOne(BusinessClient::className(), ['id' => 'reference_id']);
    }

    public function findDebitOrdersAll()
    {
        return self::find()->joinWith('reference', true, 'INNER JOIN')->all();
    }
}
