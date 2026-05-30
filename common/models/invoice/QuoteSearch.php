<?php

namespace common\models\invoice;

use common\models\business\BusinessClient;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * InvoiceSearch represents the model behind the search
 * form about `frontend\models\invoice\Invoice`.
 */
class QuoteSearch extends Quote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'business_id', 'paid', 'creditor'], 'integer'],
            [['type', 'alt_business_name', 'client_id', 'client_email', 'client_mobile', 
              'client_vat', 'reference_number', 'issue_date', 'due_date', 'comments', 'marketing', 'pdf'], 'safe'],
            [['deleted'], 'boolean'],
            [['discount', 'amount', 'vat', 'total'], 'number'],
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
     * @return SqlDataProvider
     */
    public function search($client, $params)
    {
		$type = parent::TYPE_QUOTE;
		$deleted = parent::NOT_DELETED;
		$query = parent::find();

		$count = Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM ' . parent::tableName() 
                 . ' WHERE business_id=:business_id
        		  AND type=:type 
        		  AND deleted=:deleted
        		  AND status_id=:status', [
			     ':business_id' => $client->id,
			     ':type' => $type,
			     ':deleted' => $deleted,
				 ':status'	=> parent::STATUS_SENT
		])->queryScalar();

		$dataProvider = new SqlDataProvider([
			'sql' => parent::findQuoteByBusinessId(),
			'params' => [
				':deleted' => $deleted,
				':type'	=> $type,
				':userId' => $client->user_id,
				':paid' => parent::INVOICE_UNPAID,
				':status'	=> parent::STATUS_SENT
			],
			'totalCount' => $count,
			'sort' => [
				'attributes' => [
					'business_name',
					'reference_number',
					'due_date',
				],
			],
			'pagination' => [
				'pageSize' => 10,
			]
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            $query->where('0 = 1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' 	        => $this->id,
            'status_id' 	=> $this->status_id,
            'business_id' 	=> $this->business_id,
            'deleted' 		=> $this->deleted,
            'issue_date' 	=> $this->issue_date,
            'due_date' 		=> $this->due_date,
            'discount' 		=> $this->discount,
            'amount' 		=> $this->amount,
            'paid' 			=> $this->paid,
            'creditor' 		=> $this->creditor,
            'vat' 			=> $this->vat,
            'total' 		=> $this->total,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'alt_business_name', $this->alt_business_name])
            ->andFilterWhere(['like', 'client_id', $this->client_id])
            ->andFilterWhere(['like', 'client_email', $this->client_email])
            ->andFilterWhere(['like', 'client_mobile', $this->client_mobile])
            ->andFilterWhere(['like', 'client_vat', $this->client_vat])
            ->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'marketing', $this->marketing])
            ->andFilterWhere(['like', 'pdf', $this->pdf]);

        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return SqlDataProvider
     */
    public function searchQuotesForBusiness($user_id, $params, $status)
    {
    	$type = parent::TYPE_QUOTE;
    	$business = BusinessClient::find()
                    ->where('[[user_id]]=:userId', [':userId'=>$user_id])
                    ->one();
    	$businessId = $business->id;
    	$query = self::find();
    	$count = Yii::$app->db->createCommand('
	    			SELECT COUNT(*) FROM ' . parent::tableName() . ' a
	    			WHERE deleted =:deleted
	    			AND a.type =:type
	    			AND status_id =:statusId
	    			AND (client_email = (SELECT bc.email FROM user u
	    					INNER JOIN business_client bc ON u.id = bc.user_id
	    					WHERE business_user = 1
	    					AND u.id =:userId)
	
	    				OR client_mobile = (SELECT phone_number FROM user u
	    					INNER JOIN business_client bc on u.id = bc.user_id
	    					WHERE business_user = 1
	    					AND u.id =:userId))
	    			AND a.business_id <> :businessId'
    			, [
                    ':deleted' => parent::NOT_DELETED,
                    ':type' => $type,
                    ':statusId' => $status,
                    ':userId' => $user_id,
                    ':businessId' => $businessId,
                ])->queryScalar();
    
    	$dataProvider = new SqlDataProvider([
    			'sql' => parent::findBusinessQuotesByCreditor(),
    			'params' => [
                    ':deleted' => parent::NOT_DELETED,
                    ':type' => $type,
                    ':statusId' => $status,
                    ':userId' => $user_id,
                    ':businessId' => $businessId,
                ],
    			'totalCount' => $count,
    			'sort' => [
    				'attributes' => [
    					'trading_name',
    					'amount',
    					'discount',
    					'due_date',
    				],
    			],
    			'pagination' => [
    				'pageSize' => 7,
    			]
    	]);
    
    	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to any records when validation fails
    		$query->where('0 = 1');
    		return $dataProvider;
    	}
    
    	$query->andFilterWhere([
    			'id' 	        => $this->id,
    			'status_id' 	=> $this->status_id,
    			'business_id' 	=> $this->business_id,
    			'deleted' 		=> $this->deleted,
    			'issue_date' 	=> $this->issue_date,
    			'due_date' 		=> $this->due_date,
    			'discount' 		=> $this->discount,
    			'amount' 		=> $this->amount,
    			'paid' 			=> $this->paid,
    			'creditor' 		=> $this->creditor,
    			'vat' 			=> $this->vat,
    			'total' 		=> $this->total,
    	]);
    
    	$query->andFilterWhere(['like', 'type', $this->type])
    	->andFilterWhere(['like', 'alt_business_name', $this->alt_business_name])
    	->andFilterWhere(['like', 'client_id', $this->client_id])
    	->andFilterWhere(['like', 'client_email', $this->client_email])
    	->andFilterWhere(['like', 'client_mobile', $this->client_mobile])
    	->andFilterWhere(['like', 'client_vat', $this->client_vat])
    	->andFilterWhere(['like', 'reference_number', $this->reference_number])
    	->andFilterWhere(['like', 'comments', $this->comments])
    	->andFilterWhere(['like', 'marketing', $this->marketing])
    	->andFilterWhere(['like', 'pdf', $this->pdf]);
    
    	return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return SqlDataProvider
     */
    public function searchQuotesForIndividual($user_id, $params, $status)
    {
        $type = parent::TYPE_QUOTE;
        $deleted = parent::NOT_DELETED;
    	//$individual = IndividualClient::findOne(['user_id' => $user_id]);
    	$query = self::find();
    	$count = Yii::$app->db->createCommand('
    			SELECT COUNT(*) FROM ' . parent::tableName() . ' a
    			WHERE deleted =:deleted
    			AND a.type =:type
    			AND status_id =:status
    			AND (client_id = (SELECT id_number FROM user u
    				INNER JOIN individual_client c ON u.id = c.user_id
    				WHERE business_user = 0
    				AND u.id =:id)
    
                    OR
                    
                    client_email = (SELECT c.email FROM user u
    				INNER JOIN individual_client c ON u.id = c.user_id
    				WHERE business_user = 0
    				AND u.id =:id)
    
                    OR
    
    			    client_mobile = (SELECT mobile FROM user u
    				INNER JOIN individual_client c on u.id = c.user_id
    				WHERE business_user = 0
    				AND u.id =:id))', [
                    ':deleted' => $deleted,
                    ':type' => $type,
                    ':status' => $status,
                    ':id' => $user_id
                ])->queryScalar();
    
    	$dataProvider = new SqlDataProvider([
    			'sql' => parent::findQuotesForIndividualByUserId(),
    			'params' => [
    			    ':deleted' => $deleted,
                    ':type' => $type,
                    ':status' => $status,
                    ':id' => $user_id
                ],
    			'totalCount' => $count,
    			'sort' => [
    				'attributes' => [
    					'trading_name',
    					'amount',
    					'discount',
    					'due_date',
    				],
    			],
    			'pagination' => [
    				'pageSize' => 7,
    			]
    	]);
    
    	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to any records when validation fails
    		$query->where('0 = 1');
    		return $dataProvider;
    	}
    
    	$query->andFilterWhere([
    			'id' 	        => $this->id,
    			'status_id' 	=> $this->status_id,
    			'business_id' 	=> $this->business_id,
    			'deleted' 		=> $this->deleted,
    			'issue_date' 	=> $this->issue_date,
    			'due_date' 		=> $this->due_date,
    			'discount' 		=> $this->discount,
    			'amount' 		=> $this->amount,
    			'paid' 			=> $this->paid,
    			'creditor' 		=> $this->creditor,
    			'vat' 			=> $this->vat,
    			'total' 		=> $this->total,
    	]);
    
    	$query->andFilterWhere(['like', 'type', $this->type])
    	->andFilterWhere(['like', 'alt_business_name', $this->alt_business_name])
    	->andFilterWhere(['like', 'client_id', $this->client_id])
    	->andFilterWhere(['like', 'client_email', $this->client_email])
    	->andFilterWhere(['like', 'client_mobile', $this->client_mobile])
    	->andFilterWhere(['like', 'client_vat', $this->client_vat])
    	->andFilterWhere(['like', 'reference_number', $this->reference_number])
    	->andFilterWhere(['like', 'comments', $this->comments])
    	->andFilterWhere(['like', 'marketing', $this->marketing])
    	->andFilterWhere(['like', 'pdf', $this->pdf]);
    
    	return $dataProvider;
    }
}
