<?php

namespace common\models\business;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\BusinessClient`.
 */
class BusinessClientSearch extends BusinessClient
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_id', 'title_id', 'profile_id', 'type', 'active_users', 'address_province', 
            		'rewards', 'maximum_limit_sms'], 'integer'],
            [['debit_order_start_date', 'created_at', 'updated_at'], 'safe'],
            [['email', 'contact_person', 'trading_name', 'id_number', 'initials'], 'string'],
            [['debit_order_account', 'debit_order_bank', 'debit_order_branch', 'debit_order_branch_code', 
            	'debit_order_day', 'registration_number', 'registered_name', 'vat_reg_number', 'phone_number', 
            	'contact_person', 'address_street', 'address_region', 'address_code', 'fax_number', 
            	'marketing_message'], 'string', 'max' => 255],
        	[['business_logo'], 'file', 'extensions' => 'png, jpg, gif', 'maxSize' => 2097152]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($user_id, $params)
    {
    	$business_id = BusinessClient::findOne(['user_id' => $user_id])->business_id;
        $query = BusinessClient::find();
        
        $count = $this->getDb()->createCommand('
        		SELECT COUNT(*) 
        		FROM business_client 
        		WHERE business_id=:business_id
        		OR parent_id=:parent_id',
        		[
        			':business_id' => $business_id, 
        			':parent_id' => $business_id
        				
        		])
        	->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $this->_getUsers($user_id, $business_id),
        	'params' => [
        		':business_id' => $business_id, 
        		':parent_id' => $business_id		
        	],
        	'totalCount' => $count,
        	'sort' => [
        		'attributes' => [
        			'email',
        			'item_name',
        			'status',
        		],
        	],
        	'pagination' => [
        		'pageSize' => 10,
        	]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
   
        $query->andFilterWhere([
            'user_id' 					=> $this->user_id,
        	'profile_id'				=> $this->profile_id,
            'parent_id' 				=> $this->parent_id,
            'business_id' 				=> $this->business_id,
            'title_id' 					=> $this->title_id,
            'type' 						=> $this->type,
            'active_users' 				=> $this->active_users,
        	'address_province' 			=> $this->address_province,
        	'rewards'					=> $this->rewards,
        	'maximum_limit_sms'			=> $this->maximum_limit_sms,
        	'debit_order_start_date'	=> $this->debit_order_start_date,
            'created_at' 				=> $this->created_at,
            'updated_at' 				=> $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'trading_name', $this->trading_name])
            ->andFilterWhere(['like', 'registration_number', $this->registration_number])
            ->andFilterWhere(['like', 'registered_name', $this->registered_name])
            ->andFilterWhere(['like', 'vat_reg_number', $this->vat_reg_number])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'address_street', $this->address_street])
            ->andFilterWhere(['like', 'address_region', $this->address_region])
            ->andFilterWhere(['like', 'address_code', $this->address_code])
            ->andFilterWhere(['like', 'fax_number', $this->fax_number])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'debit_order_account', $this->debit_order_account])
            ->andFilterWhere(['like', 'debit_order_bank', $this->debit_order_bank])
       		->andFilterWhere(['like', 'debit_order_branch_code', $this->debit_order_branch_code])
       		->andFilterWhere(['like', 'debit_order_day', $this->debit_order_day])
       		->andFilterWhere(['like', 'marketing_message', $this->marketing_message]);

        return $dataProvider;
    }
}
