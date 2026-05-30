<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\business\BusinessClient;

/**
 * Business client Credit Policy model
 */
class CreditPolicyForm extends Model
{

	protected $_business_client;
    /**
     * @var string $terms credit terms
     */
    public $terms;

    public function rules()
    {
    	return [
    		['terms', 'required', 'message' => 'Field is required'],
    		['terms', 'string', 'max' => 255, 'message' => 'Text is too large'], 
    	];
    }
    
    public function getClient($user_id){
    	$client = null;
    	
    	if(is_null($client))
    		$client = BusinessClient::findOne(['user_id' => $user_id]);
    	
    	return $client;
    }
    
    public function hasCreditPolicy($user_id) 
    {
    	$client = $this->getClient($user_id);
    	if($client) {
    		if(!empty($client->credit_terms))
    			return true;
    	}
    	
    	return false;
    }
    
    public function getClientCreditPolicy($user_id)
    {
    	$client = $this->getClient($user_id);
    	if($client) {
    		return $client->credit_terms;
    	}
    	 
    	return false;
    }
    
    /**
     * Update business credit policy.
     *
     * @return boolean if updated or otherwise.
     */
    public function updatePolicy($user_id)
    {
        $client = $this->getClient($user_id);
        if($client) {
        	$client->credit_terms = $this->terms;
        	if($client->save(false)) {
        		return true;
        	}
        }
        
        return false;
        
    }
}
