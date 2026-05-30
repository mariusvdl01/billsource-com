<?php

namespace common\modules\mygate;

use Yii;
use DateTime;
use yii\db\Command;
use common\helpers\ArrayHelper;
use common\models\payment\PaymentFees;
use common\models\invoice\Invoice;

class MyGate extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\mygate\controllers';
	protected $command = null;
    protected $rpp = 'https://www.mygate.co.za/virtual/8x0x0/dsp_ecommercepaymentparent.cfm';
	//protected $_rpp = 'https://dev-virtual.mygateglobal.com/PaymentPage.cfm';

    public function init()
    {
        parent::init();
        
        // custom initialization code goes here
        if(is_null($this->command)) {
            $this->command = new Command;
            $this->command->db = Yii::$app->db;
        }
    }
    
    public function getRpp()
    {
    	return $this->rpp;
    }
    
    public function getReturnUrl()
    {
    	return Yii::$app->request->getHostInfo() . '/mygate/validation';
    }
    
    public function processTransaction($post)
    {
    	$valid = false;
    	$user = Yii::$app->user->identity;
    	$client = 'IND';
    	if($user->business_user)
    		$client = 'BIZ';
    	
    	$now = (new \DateTime())->format('Y-m-d H:i:s');
    	$command = $this->command;
    	$result = $this->validateResult($post);
    	$data = [
    		'paid' => (isset($post['_RESULT']) && $post['_RESULT'] == '0') ? '1' : '0',
    		'response_time' => $now,
    		'receipt_id' => (isset($post['VARIABLE1']) && $post['VARIABLE1'] != '') ? $post['VARIABLE1'] : '0',
    		'response_3d_status' => (isset($post['_3DSTATUS']) && $post['_3DSTATUS'] != '') ? $post['_3DSTATUS'] : null,
    		'response_error_code' => (isset($post['_ERROR_CODE']) && $post['_ERROR_CODE'] != '') ? $post['_ERROR_CODE'] : null,
    		'response_error_details' => (isset($post['_ERROR_DETAIL']) && $post['_ERROR_DETAIL'] != '') ? $post['_ERROR_DETAIL'] : null,
    		'response_bank_error_code' => (isset($post['_BANK_ERROR_CODE']) && $post['_BANK_ERROR_CODE'] != '') ? $post['_BANK_ERROR_CODE'] : null,
    		'response_bank_error_details' => (isset($post['_ERROR_MESSAGE'] ) && $post['_ERROR_MESSAGE'] != '') ? $post['_ERROR_MESSAGE'] : null,
    		'response_result' => (isset($post['_RESULT']) && $post['_RESULT'] != '') ? $post['_RESULT'] : null,
    		'response_bank_error_message' => (isset($post['_BANK_ERROR_MESSAGE']) && $post['_BANK_ERROR_MESSAGE'] != '') ? $post['_BANK_ERROR_MESSAGE'] : null,
    		'response_error_source' => (isset($post['_ERROR_SOURCE']) && $post['_ERROR_SOURCE'] != '') ? $post['_ERROR_SOURCE'] : null,
    	];
    	
    	$command->update('receipt', $data, "receipt_id = {$post['VARIABLE1']}")->execute();
    	if(isset($post['_RESULT']) && isset($post['VARIABLE1']))
    	{
    		$data = [
    			'payment_result' => $post['_RESULT'],	
    		];
    		$command->update('invoice_payment', $data, "pay_index = {$post['VARIABLE1']}")->execute();
    	
    		if($post['_RESULT'] == '0')
    		{
    			$sql = 'UPDATE ' . Invoice::tableName() . ' SET `paid` = 1, status_id = 3 
    					WHERE `invoice_id` IN  
    										(SELECT `invoice_id` 
    										FROM `invoice_payment` 
    										WHERE `payment_result` = 0 
    										AND `pay_index` = ' . $post['VARIABLE1'] . ')';
    			$command->setSql($sql)->execute();
    			
    			$sql = 'UPDATE `payment_fees` SET `fee_paid` = ' . PaymentFees::TRANX_FEE_PAID  
    					.' WHERE `payment_index` = ' . $post['VARIABLE1'];
    			$command->setSql($sql)->execute();
    			
    			$sql = "INSERT INTO `reward` (`reference_type`, `reference_id`, `description`, `amount`, `created_at`)
    					SELECT '$client', `a`.`user_id` , 'Payment rewards', FLOOR(SUM(`payment_amount`)) `inv_total`, CURRENT_TIMESTAMP
    					FROM `invoice_payment` `a` 
    					INNER JOIN `user` `u` ON `u`.`user_id` = `a`.`user_id` 
    					WHERE `payment_result` = 0 
    					AND `pay_index` = {$post['VARIABLE1']}
    					GROUP BY `payment_reference` LIMIT 0 , 1 ";
    			if($command->setSql($sql)->execute() !== false) {
    				$sql = "SELECT SUM(`amount`) `rewards` 
    						FROM `reward` 
    						WHERE `reference_type` = '$client' 
    						AND `reference_id` = (SELECT `user_id` 
    												FROM `user` 
    												WHERE `user_id` = $user->user_id)";

    				$data = $command->setSql($sql)->queryAll();
    				
    				if($data) {
    					$row = [];
    					ArrayHelper::recursive($data, $row);
  
    					$sql = 'UPDATE ';
    					if($client == 'IND')
    						$sql .= '`individual_client` ';
    					else
    						$sql .= '`business_client` ';
    						
    					$sql .= 'SET `rewards` = ' . $row['rewards'] . ' 
    							WHERE `user_id` = 
    											(SELECT `user_id` 
    											FROM `user` 
    											WHERE `user_id` = ' . $user->user_id . ')';
    					$command->setSql($sql)->execute();
    				}
    			}
    			$valid = true;
    		}
    	}
    	return array('valid' => $valid, 'result' => $result);
    }
    
    protected function validateResult($post)
    {
    	$messages = '';
    
    	if(!isset($post['_RESULT']))
    		$messages[] = 'Invalid mygate result. No results posted.';
    	else if($post['_RESULT'] != '0' && isset($post['_ERROR_MESSAGE']))
    		$messages[] = $post['_ERROR_MESSAGE'];
    	if(!isset($post['VARIABLE1']))
    		$messages[] = 'Invalid transaction ID';
    
    	return $messages;    
    }
}
