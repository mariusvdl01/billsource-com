<?php

namespace common\models\individual;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Request for assistance form
 */
class AssistanceForm extends Model
{
		
	/**
	 * Request for assistance submitted
	 * 
	 * @var boolean $submit_assistance checks if assistance is submitted or otherwise
	 */
    public $submit_assistance;
    
    /**
     * Agrees with terms and conditions
     * 
     * @var boolean $assistance_agree_terms checks if terms was agreed to or otherwise
     */
    public $assistance_agree_terms;
	
	/**
	 * Confirms if user profile is update
	 * 
	 * @var boolean $assistance_update checks if user profile is up to date
	 */
    public $assistance_update;
    
    /**
     * Confirms if user should be contacted
     * 
     * @var boolean $assistance_contact checks if user agrees to to be contacted
     */
    public $assistance_contact;

    /**
     * Validation rules to apply to class properties
     * 
     * @return array $array of validation rules
     */
    public function rules()
    {
        return [
        	[['submit_assistance', 'assistance_agree_terms', 'assistance_update', 
        			'assistance_contact'], 'boolean'],
        	[['submit_assistance', 'assistance_agree_terms', 'assistance_update', 
        			'assistance_contact'], 'compare', 'compareValue' => true, 'message' => 'Field is required'],
        ];
    }

    /**
     * Submits user request for counselling
     *
     * @param integer $user_id the user id
     * @return boolean model is saved successfully or otherwise.
     */
    public function submitCounsellingRequest($user_id)
    {
        if ($this->validate()) {
            $client = $this->findIndividualClient($user_id);
            if($client && !$client->submit_assistance)	{
            	$client->submit_assistance = $this->submit_assistance;
            	$client->assistance_agree_terms = $this->assistance_agree_terms;
            	$client->assistance_update = $this->assistance_update;
            	$client->assistance_contact = $this->assistance_contact;
            	
            	if($client->save(false))
           			return $this->sendEmail($user_id);
            }
        	return false;
    	}
    }
    
    protected function findIndividualClient($id) 
    {
        if (($model = IndividualClient::find()
                ->where('[[user_id]]=:user_id', ['user_id' => $id])
                ->joinWith('province', true, 'INNER JOIN')
                ->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The user does not exist.');
        }
    }

    /**
     * Submits user request for loan
     *
     * @return boolean email is sent successfully.
     */
    public function submitLoanRequest($user_id)
    {
    	if ($this->validate()) {
    		return $this->sendEmail($user_id, 'loan');
    	}
    	return false;
    }
    
    /**
     * Sends an email with a link, for validating email.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail($user_id, $type = 'assistance')
    {
		$user = $this->findIndividualClient($user_id);
		
    	$emails = [
			'carina@cilreyn.co.za',
			'billsource.service@gmail.com',
		];
		
    	if($user) {
	    	return Yii::$app->mailer->compose([
	    			'html' => 'requestAssistance-html',
	    			'text' => 'requestAssistance-text'
	    		],
	    		[
	    			'user' => $user,
	    			'type' => $type,
	    		])
	    		->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
	    		->setTo($emails)
	    		->setSubject($type=='loan' ? 'Billsource - Loan request' : 'Billsource - Debt counseling')
	    		->send();
    	}
    	return false;
	}
}