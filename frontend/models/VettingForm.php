<?php
namespace frontend\models;

use common\models\invoice\Invoice;
use yii\base\Model;

/**
 * User registration/signup form
 * 
 */
class VettingForm extends Model
{	
	/**
	 * Placeholder of client ID
	 * 
	 * @var string $refernce client ID
	 */
    public $reference;
    /**
     * 
     * 
     * @var float $_total invoice total amount
     */
    protected $_total;
    
    public function rules()
    {
        return [
        	['reference', 'filter', 'filter' => function($value) {
        			return str_replace(['/', '-', '_', ' '], '', $value);
        		}
        	],
        	['reference', 'required', 'message' => 'Field required'],
        	['reference', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * Vet user or business.
     *
     * @return boolean | if business has credit history or otherwise
     */
    public function vetBusiness()
    {
        $query = Invoice::find();
        $data = $query->select("SUM(amount) AS total_amount")
        				->where('[[client_id]]=:client_id', [':client_id' => $this->reference])
        				->orWhere('[[client_mobile]]=:client_mobile', [':client_mobile' => $this->reference])
        				->orWhere('[[client_email]]=:client_email', [':client_email' => $this->reference])
        				->andWhere('[[deleted]]=:deleted', [':deleted' => 0])
            ->andWhere('[[paid]]=:paid', [':paid' => 0])
        					->createCommand()
        						->queryOne();
        
        return $data === false ? '' : ($this->_total = $data['total_amount']);
    }
    
    public function getTotal()
    {
    	return $this->_total;
    }
}
