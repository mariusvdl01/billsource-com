<?php

namespace common\models\invoice;

use common\models\business\BusinessClient;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * InvoiceSearch represents the model behind the search form about `common\models\invoice\Invoice`.
 */
class InvoiceSearch extends Invoice
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
    public function search($client, $params, $type = 'INV')
    {
    	$business_id = $client->id;
        $query = Invoice::find();
        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM ' . Invoice::tableName() . ' `a` 
            INNER JOIN  `business_client` `b` ON `a`.`business_id` = `b`.`id`
            INNER JOIN `user` `u` ON `b`.`user_id` = `u`.`id` 
            INNER JOIN invoice_age_type ON age_paid = paid
    				        AND (age_paid = 1 OR (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    			            AND DATEDIFF(NOW(), due_date) <= maximum_days))
    					INNER JOIN `status` `s` ON `a`.`status_id` = `s`.`id`
    					LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `p`
    			        ON p.invoice_id = a.id
            WHERE business_id=:business_id
            AND a.type=:type
            AND paid=:paid
            AND deleted=:deleted
            AND u.id=:userId
            AND status_id=:status',[
        			':business_id' 	=> $business_id,
        			':type' 	   	=> $type,
					':paid'			=> parent::INVOICE_UNPAID,
        			':deleted'     	=> parent::NOT_DELETED,
					':userId'       => $client->user_id,
					':status'		=> parent::STATUS_SENT,
        		])->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => parent::findInvoiceByUserId(),
        	'params' => [
		        ':deleted'  => parent::NOT_DELETED,
        		':type'		=> $type,
				':paid'		=> parent::INVOICE_UNPAID,
		        ':userId' 	=> $client->user_id,
				':status'	=> parent::STATUS_SENT
        	],
        	'totalCount' => $count,
        	'sort' => [
        		'attributes' => [
        			'reference_number',
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
	public function searchAllDebtors($user_id, $params, $type = 'INV')
	{
		$business_id = BusinessClient::findOne(['user_id' => $user_id])->id;
		$query = Invoice::find();
		$count = Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM ' . Invoice::tableName() . ' 
        		WHERE business_id=:business_id
        		AND type=:type
        		AND deleted=:deleted',[
			':business_id' 	=> $business_id,
			':type' 	   	=> $type,
			':deleted'     	=> parent::NOT_DELETED,
		])->queryScalar();

		$dataProvider = new SqlDataProvider([
			'sql' => parent::findAllBillsForDebtors(),
			'params' => [
				':deleted'  => parent::NOT_DELETED,
				':type'		=> $type,
				':userId' 	=> $user_id,
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
	public function searchAllCreditors($user_id, $params, $type = 'INV')
	{
		$query = Invoice::find();
		$count = Yii::$app->db->createCommand('
    			(SELECT COUNT(*) FROM ' . Invoice::tableName() . ' a
    			WHERE deleted =:deleted
    			AND a.type =:type
    			AND client_email IN
    					(SELECT bc.email FROM user u
    					INNER JOIN business_client bc ON u.id = bc.user_id
    					AND business_user = 1
    					AND u.id =:userId))

    			UNION

    			(SELECT COUNT(*) FROM ' . Invoice::tableName() . ' a
    			WHERE deleted =:deleted
    			AND a.type =:type
    			AND client_id IN
    					(SELECT registration_number FROM user u
    					INNER JOIN business_client bc ON u.id = bc.user_id
    					AND business_user = 1
    					AND u.id =:userId))
    			', [
			':deleted' => parent::NOT_DELETED,
			':type' => $type,
			':userId' => $user_id
		])->queryScalar();

		$dataProvider = new SqlDataProvider([
			'sql' => parent::findAllBillsByCreditor(),
			'params' => [
				':deleted' => parent::NOT_DELETED,
				':type' => $type,
				':userId' => $user_id
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
    public function searchCreditorsByPaymentStatus($user_id, $params, $paid = 0, $type = 'INV')
    {
    	$query = Invoice::find();
    	$count = Yii::$app->db->createCommand('
    			(SELECT COUNT(*) FROM ' . Invoice::tableName() . ' a
    			WHERE deleted =:deleted 
    			AND a.type =:type 
                AND a.paid =:paid
    			AND client_email IN 
    					(SELECT bc.email FROM user u 
    					INNER JOIN business_client bc ON u.id = bc.user_id  
    					AND business_user = 1 
    					AND u.id =:userId))
    			
    			UNION
    			
    			(SELECT COUNT(*) FROM ' . Invoice::tableName() . ' a
    			WHERE deleted =:deleted
    			AND a.type =:type
                AND a.paid =:paid
    			AND client_id IN 
    					(SELECT registration_number FROM user u 
    					INNER JOIN business_client bc ON u.id = bc.user_id
    					AND business_user = 1 
    					AND u.id =:userId))
    			', [
                    ':deleted' => parent::NOT_DELETED,
                    ':type' => $type,
                    ':paid' => $paid,
                    ':userId' => $user_id
                ])->queryScalar();
    
    	$dataProvider = new SqlDataProvider([
    			'sql' => parent::findBusinessBillsByCreditor(),
                'params' => [
                    ':deleted' => parent::NOT_DELETED,
                    ':type' => $type,
                    ':paid' => $paid,
                    ':userId' => $user_id
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
    public function searchForIndividual($user_id, $params, $paid = 0, $state = parent::STATUS_SENT, $type = 'INV')
    {
    	$deleted = self::NOT_DELETED;
    	$query = self::find();
    	$count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM ' . Invoice::tableName() . ' 
                  WHERE `paid` =:paid
                  AND `deleted` =:deleted
                  AND `type` =:type
                  AND `status_id` =:status
                  AND (`client_id` IN 
                    (SELECT `id_number` FROM `user` `u`
                    INNER JOIN `individual_client` `d` ON `d`.`user_id` = `u`.`id`   
                    WHERE `business_user` = 0 AND `u`.`id` =:userId)
                    
                    OR `client_email` IN 
                    (SELECT `d`.`email` FROM `user` `u`
                    INNER JOIN `individual_client` `d` ON `d`.`user_id` = `u`.`id`
                    WHERE `business_user` = 0 AND `u`.`id` =:userId)
                    
                    OR `client_mobile` IN 
                    (SELECT `mobile` FROM `user` `u`
                    INNER JOIN `individual_client` `d` ON `d`.`user_id` = `u`.`id`
                    WHERE `business_user` = 0 AND `u`.`id` =:userId)
                  )', [
                ':paid' => $paid,
                ':deleted' => $deleted,
                ':type' => $type,
                ':status' => $state,
                ':userId' => $user_id
            ])->queryScalar();
    
    	$dataProvider = new SqlDataProvider([
    			'sql' => parent::findIndividualBills(),
    			'params' => [
    			    ':paid' => $paid,
                    ':deleted' => $deleted,
                    ':type' => $type,
                    ':status' => $state,
                    ':userId' => $user_id
                ],
    			'totalCount' => $count,
    			'sort' => [
    				'attributes' => [
    					'due_date',
                        'reference_number',
                        'trading_name'
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
    public function searchPaid($client, $params)
    {
    	$business_id = $client->id;
    	$paid = parent::INVOICE_PAID;
    	$type1 = parent::TYPE_INVOICE;
        $type2 = parent::TYPE_CASH_INVOICE;
    	$deleted = parent::NOT_DELETED;
		$statusPaid = parent::STATUS_PAID;
		$statusUnpaid = parent::STATUS_UNPAID;

    	$query = Invoice::find();
    	$count = Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM ' . Invoice::tableName() . ' 
                 WHERE business_id=:business_id
        		AND paid=:paid
        		AND status_id=:status
                AND (type=:type1 OR type=:type2)
    			AND deleted=:deleted', [
        			':business_id' 	=> $business_id, 
        			':paid' 		=> $paid,
					':status'		=> [$statusPaid, $statusUnpaid],
        			':type1' 		=> $type1,
                    ':type2'        => $type2,
    				':deleted'		=> $deleted
        		])->queryScalar();
    
    	$dataProvider = new SqlDataProvider([
    			'sql' => parent::findInvoiceByPaymentStatus($business_id, $paid, $type1, $type2),
    			'params' => [
                    ':paid'         => $paid,
    				':business_id' 	=> $business_id, 
					':status'		=> [$statusPaid, $statusUnpaid],
    				':type1' 		=> $type1, 
                    ':type2'        => $type2,
    				':deleted'		=> $deleted
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
    public function searchUnpaid($user_id, $params)
    {
        $business_id = BusinessClient::findOne(['user_id' => $user_id])->business_id;
        $paid = self::INVOICE_UNPAID;
        $type = self::TYPE_INVOICE;
        $deleted = self::NOT_DELETED;
        $query = Invoice::find();
        $count = Yii::$app->db->createCommand('
                 SELECT COUNT(*) FROM ' . Invoice::tableName() . ' 
                 WHERE business_id=:business_id
                AND paid=:paid 
                AND type=:type 
                AND deleted=:deleted', [
                    ':business_id'  => $business_id, 
                    ':paid'         => $paid,
                    ':type'         => $type,
                    ':deleted'      => $deleted
                ])->queryScalar();
    
        $dataProvider = new SqlDataProvider([
                'sql' => parent::findInvoiceByPaymentStatus($business_id, $paid, $type),
                'params' => [
                    ':business_id'  => $business_id, 
                    ':paid'         => $paid,
                    ':type'         => $type, 
                    ':deleted'      => $deleted
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
                'invoice_id'    => $this->invoice_id,
                'status_id'     => $this->status_id,
                'business_id'   => $this->business_id,
                'deleted'       => $this->deleted,
                'issue_date'    => $this->issue_date,
                'due_date'      => $this->due_date,
                'discount'      => $this->discount,
                'amount'        => $this->amount,
                'paid'          => $this->paid,
                'creditor'      => $this->creditor,
                'vat'           => $this->vat,
                'total'         => $this->total,
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
	public function searchByState($client, $params, $status)
	{
		$business_id = $client->id;
		$paid = parent::INVOICE_UNPAID;
		$type = parent::TYPE_INVOICE;
		$deleted = parent::NOT_DELETED;

		$query = Invoice::find();
		$count = Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM ' . Invoice::tableName() . ' 
                 WHERE business_id=:business_id
        		AND paid=:paid AND type=:type AND status_id=:status_id
    			AND deleted=:deleted', [
			':business_id' 	=> $business_id,
			':paid' 		=> $paid,
			':type' 		=> $type,
			':status_id' 	=> $status,
			':deleted'		=> $deleted
		])->queryScalar();

		$dataProvider = new SqlDataProvider([
			'sql' => self::findInvoiceByState(),
			'params' => [
                ':paid'         => $paid,
                ':deleted'      => $deleted,
                ':business_id'  => $business_id,
                ':type'         => $type,
                ':status_id'    => $status
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