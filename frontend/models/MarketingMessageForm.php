<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\business\BusinessClient;

/**
 * Business client Marketing Message Form model. It is a helper form to
 * update the marketing message displayed in quotes and invoices generated
 * by the client
 */
class MarketingMessageForm extends Model
{

	protected $_businessClient;

    /**
     * @var string $marketing_message marketing_message
     */
    public $marketing_message;

    public function rules()
    {
    	return [
    		['marketing_message', 'required', 'message' => 'Field is required'],
    		['marketing_message', 'string', 'max' => 255, 'message' => 'Marketing message is too large (1 - 255 characters)'], 
    	];
    }
    
    /**
     * Return business client model
     *
     * @param int $user_id user ID of authenticate business client
     * @return BusinessClient the model of the current login in client
     */
    public function getClient($user_id){
    	$client = null;
    	
    	if(is_null($client))
    		$client = BusinessClient::findOne(['user_id' => $user_id]);
    	
    	return $client;
    }
    
    /**
     * Checks if client has existing marketing message
     *
     * @param int $user_id user ID of authenticate business client
     * @return boolean if marketing message exists or otherwise
     */
    public function hasMarketingMessage($user_id) 
    {
    	$client = $this->getClient($user_id);
    	if($client) {
    		if(!empty($client->marketing_message))
    			return true;
    	}
    	
    	return false;
    }
    
    /**
     * Return client marketing message
     *
     * @param int $user_id user ID of authenticate business client
     * @return boolean if marketing message exists or otherwise
     */
    public function getClientMarketingMessage($user_id)
    {
    	$client = $this->getClient($user_id);
    	if($client) {
    		return $client->marketing_message;
    	}
    	 
    	return false;
    }
    
    /**
     * Update business marketing message.
     *
     * @param int $user_id user ID of authenticate business client
     * @return boolean if updated or otherwise.
     */
    public function updateMessage($user_id)
    {
        $client = $this->getClient($user_id);
        if($client) {
        	$client->marketing_message = $this->marketing_message;
        	return $client->save(false);
        }
        
        return false;
        
    }
}
