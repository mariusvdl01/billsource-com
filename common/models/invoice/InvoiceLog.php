<?php

namespace common\models\invoice;

use common\models\business\BusinessClient;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "invoice_log".
 *
 * @property integer $business_id
 * @property string $period
 * @property integer $count
 *
 * @property BusinessClient $business
 */
class InvoiceLog extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_log';
    }

    public function behaviors()
    {
    	return [
    		[
    			'class' => TimestampBehavior::className(),
    			'createdAtAttribute' => false,
    			'updatedAtAttribute' => false,
    		]
    	];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'period', 'count'], 'required'],
            [['business_id', 'count'], 'integer'],
            [['period'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_id' => Yii::t('app', 'Business ID'),
            'period' => Yii::t('app', 'Period'),
            'count' => Yii::t('app', 'Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessClient::className(), ['id' => 'business_id']);
    }
    
    public static function replaceClientInvoiceLog($business_id)
    {
    	$sql = '';
    	$counter = 1;
    	$data = self::getClientInvoiceLog($business_id);
    	
    	if(isset($data['business_id']))
    	{
    		$sql = 'REPLACE INTO ' . self::tableName() . ' (`business_id`, `period`, `count`) 
    				VALUES(' . $data['business_id'] . ',\'' . $data['date'] . '\',' . ($data['count'] + $counter) . ')';
    	} else {
    		$sql = 'INSERT INTO ' . self::tableName() . ' (`business_id`, `period`, `count`)
    				VALUES(' . $business_id . ',\'' . $data['date'] . '\',' . ($data['count'] + $counter) . ')';
    	}
    	self::findBySql($sql)->createCommand()->execute();
    }
    
    public static function getClientInvoiceLog($business_id)
    {
    	$query = 'SELECT `a`.`business_id` , IFNULL(`period`, DATE_FORMAT(CURRENT_DATE(), \'%Y%m\')) `date`,
    			IFNULL(`count`, 0) `count`, `maximum_limit_invoices` 
    			FROM `business_client` `bc` 
    			LEFT JOIN `business_profile` `bp` ON `bc`.`profile_id` = '.'`bp`.`id` 
    			LEFT JOIN (SELECT * FROM `invoice_log` `il` WHERE `period`  = 
    						DATE_FORMAT(CURRENT_DATE(), \'%Y%m\')) `a` 
    					  ON `a`.`business_id` = `bc`.`id`  
    					  WHERE `bc`.`id` = :id';
    	
    	$data = self::findBySql($query, [
    	    ':id' => $business_id
        ])->createCommand()->queryOne();
    	
    	return $data === false ? '' : $data;
    }
    
    public static function canCreateBill($biz_id)
    {
    	$data = self::getClientInvoiceLog($biz_id);
    	if($data) {
    		if($data['count'] >= $data['maximum_limit_invoices']) {
    			return false;
    		}
    	}
    	return true;
    }
}
