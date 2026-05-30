<?php

namespace common\models\individual;

use common\models\invoice\Invoice;
use common\models\invoice\InvoiceLine;
use common\models\Reading;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "individual_reading".
 *
 * @property integer $id
 * @property integer $individual_id
 * @property integer $read_id
 * @property integer $invoice_line_id
 * @property string $reading_month
 * @property string $reading_previous
 * @property string $reading_current
 * @property string $created_at
 *
 * @property IndividualClient $individual
 */
class IndividualReading extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%individual_reading}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
    	return [
    		[
    			'class' => TimestampBehavior::className(),
    			'updatedAtAttribute' => false,
    			'value' => function() {
    				$now = new DateTime();
    				return $now->format('Y-m-d H:i:s');
    			},
    		]
    	];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['individual_id', 'read_id', 'invoice_line_id', 
            'reading_month', 'reading_current', 'reading_previous'], 'required'],
            [['individual_id', 'read_id', 'invoice_line_id', 'reading_previous', 
            		'reading_current'], 'integer'],
            [['created_at'], 'safe'],
            [['reading_month'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'individual_id' => Yii::t('app', 'Individual ID'),
            'read_id' => Yii::t('app', 'Read ID'),
            'invoice_line_id' => Yii::t('app', 'Invoice Line ID'),
            'reading_month' => Yii::t('app', 'Period'),
            'reading_previous' => Yii::t('app', 'Previous Meter Reading'),
            'reading_current' => Yii::t('app', 'Current Meter Reading'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividual()
    {
        return $this->hasOne(IndividualClient::className(), ['id' => 'individual_id']);
    }
    
    public function getReading()
    {
    	return $this->hasOne(Reading::className(), ['id' => 'read_id']);
    }
    
    public function findAllReadings($user_id, $utility_id)
    {
    	$query = 'SELECT `b`.`invoice_line_id`, `b`.`id` AS `ind_read_id`, `c`.`id`, `individual_id`,
    			`read_id`, IFNULL(`reading_month`, DATE_FORMAT(CURRENT_DATE(), \'%Y%m\')) 
    			`reading_month`, `image`, IFNULL(`line_description`, `c`.`description`) 
    			`line_desc`, `reading_previous`, `reading_current`, `line_qty`, `line_unit_price`
    			FROM `reading` `c`
    			LEFT JOIN `individual_reading` `b` ON `reading_month` = DATE_FORMAT(CURRENT_DATE(), \'%Y%m\')
    			AND `c`.`id` = `b`.`read_id`
    			LEFT JOIN `invoice_line` `a` ON `b`.`invoice_line_id` = `a`.`id`
    			LEFT JOIN (SELECT * FROM `user` WHERE `user`.`id` =:user_id) `e` 
    			ON `business_user` = 0
    			WHERE `c`.`id` =:id
    			ORDER BY `c`.`id` ASC';
    	
    	$data = self::findBySql($query, [':user_id'=>$user_id, ':id'=>$utility_id])->createCommand()->queryAll();
    	
    	return $data === false ? false : $data;
    }
    
    public function saveReadings($user_id) 
    {
    	$id = IndividualClient::findOne(['user_id'=>$user_id])->id;
    	$utility_id = (int) Yii::$app->request->post('utility');
        $rows = $this->findAllReadings($user_id, $utility_id);
    	if($rows)
    	{
    		$invoice_id = 0;
            $readings = $this->getPreviousUtilityBill($user_id);

    		if($readings && isset($readings[0]['invoice_id']))
    			$invoice_id = $readings[0]['invoice_id'];
    		
    		foreach($rows as $row) {
    			if(isset($row['id']) && $row['id']) {
    				if($invoice_id == 0) {
    					$invoice_id = $this->insertUtilityBill($id);
    				}
    				
    				if(!isset($row['invoice_line_id'])) {
    					$row['invoice_line_id'] = $this->insertUtilityBillLineItem($invoice_id);
    				} else {
    					$this->updateUtilityBillLineItem($row);
    				}
    				
    				if(!isset($row['ind_read_id']) ) {
    					$now = new DateTime;
    					$sql = 'INSERT INTO `individual_reading`( `individual_id`,
    							`read_id`, `invoice_line_id`,
    							`reading_month`, `reading_previous`, 
    							`reading_current`, `created_at`) 
    							VALUES ('
    									.$id.','.Yii::$app->request->post('utility').','.
    									((isset($row['invoice_line_id'])) ? $row['invoice_line_id'] : '0')
    									.',\''.$row['reading_month'].'\','.$this->reading_previous
    									.','.$this->reading_current.',\''. $now->format('Y-m-d H:i:s').'\')';
    				} else {
    					$sql = 'UPDATE `individual_reading` SET `reading_previous`='.$this->reading_previous
    							.',`reading_current`='.$this->reading_current
    							.',`invoice_line_id`='.((isset($row['invoice_line_id'])) ? $row['invoice_line_id'] : '0').' WHERE '
    							.'`read_id`='.$row['read_id'];
    				}
    				
    				self::findBySql($sql)->createCommand()->execute();
    			}
    		}
    		
    		if($invoice_id) {
    			$sql = 'SELECT (SUM(`line_amount`)) AS `amount`, (SUM(`line_amount`)*.14) AS `vat`, 
    					(SUM(`line_amount`)*1.14) AS `total` 
    					FROM `invoice_line` 
    					WHERE `invoice_id` = '. $invoice_id;
    	
    			if(false !== ($result = self::findBySql($sql)->createCommand()->queryOne())) {
    				$sql = 'UPDATE ' . Invoice::tableName() . ' SET `amount`='.$result['amount']
    						.',`vat`='.round($result['vat'], 2).',`total`='
    						.round($result['total'], 2).' WHERE `id`='. $invoice_id;
    				self::findBySql($sql)->createCommand()->execute();
    			}
    		}
    	}
    	return true;
    }
    
    private function getPreviousUtilityBill($user_id)
    {
    	$sql = "SELECT `d`.`invoice_id`, `b`.`email`
            	FROM `individual_client` `b`
            	INNER JOIN (SELECT * FROM `user` WHERE `id` = '.$user_id.') `a`
            	ON `a`.`business_user` = 0
            	AND `b`.`user_id` = `a`.`id`
            	LEFT JOIN `individual_reading` `c` ON `c`.`individual_id` = `b`.`id`
            	LEFT JOIN `invoice_line` `d` ON `reading_month` = DATE_FORMAT(CURRENT_DATE(), '%Y%m')
            	AND `c`.`invoice_line_id` = `d`.`id` 
    			LIMIT 0,1";
    	
    	return self::findBySql($sql)->createCommand()->queryOne();
    }
    
    private function insertUtilityBill($id)
    {
    	$sql = "INSERT INTO " . Invoice::tableName() . "(`business_id`, `alt_business_name`, `type`, `deleted`, 
    	    `client_id`, `client_email`, `client_mobile`, `client_vat`, `reference_number`, `issue_date`, `due_date`,
    			`discount`, `amount`, `paid`, `comments`, `marketing`, `creditor`, `vat`, `total`, `pdf`
    		)
    		(SELECT 0, 'Utility Bill', '" . Invoice::TYPE_UTILITY_BILL . "', 0, id_number, email, mobile, NULL, 
    		    CONCAT('BSS-', DATE_FORMAT(CURRENT_DATE(), '%Y%m'), '-', id), CURRENT_DATE(), CURRENT_DATE(), 0, 
    		    0, 0, 'Self Captured Utility Reading', NULL, 0, 0, 0, NULL 
    			FROM `individual_client` 
    			WHERE `id` = $id LIMIT 0,1
    		)";
    	
    	$result = Invoice::findBySql($sql)->createCommand()->execute();  
    	if($result !== false){
    		return Invoice::getDb()->getLastInsertID();
    	}
    	return false;
    }
    
    private function insertUtilityBillLineItem($invid)
    {
    	$post = Yii::$app->request->post();
    	$desc = Reading::findOne($post['utility'])->description;
    	
    	$sql = "INSERT INTO `invoice_line`( `invoice_id`, `line_description`, `line_amount`, `line_qty`,
    			`line_unit_price`, `line_progress_value`, `line_progress_maximum`) 
    			VALUES (" . $invid . ",'" . $desc . "'," . 
    					round(
    						(round($this->reading_current, 2) 
    						- round($this->reading_previous, 2))
    						* round($post['rate'], 10), 
    					2) . "," .
    					(round($this->reading_current, 2) - round($this->reading_previous, 2))
    					. "," .round($post['rate'], 10) . ", NULL, NULL)";
    	
    	$result = InvoiceLine::findBySql($sql)->createCommand()->execute();
    	if($result !== false){
    		return InvoiceLine::getDb()->getLastInsertID();
    	}
    	return false;
    	
    }
    
    private function updateUtilityBillLineItem($row)
    {
    	$post = Yii::$app->request->post();
    	$desc = Reading::findOne($post['utility'])->description;
    	
    	$sql = 'UPDATE `invoice_line` SET `line_description`= \'' . $desc
    			. '\', `line_amount`='
    					. round(
    						(round($this->reading_current, 2) 
    						- round($this->reading_previous, 2))
    						*round($post['rate'],10),
    					2)
    			.',`line_qty`='.(round($this->reading_current, 2) - round($this->reading_previous, 2))
    			.',`line_unit_price`=' . round($post['rate'], 10)
    			.' WHERE `invoice_line_id`='. $row['invoice_line_id'];
    }
}
