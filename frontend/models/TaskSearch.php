<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\SqlDataProvider;
use common\models\Task;

/**
 * TaskSearch represents the model behind the search form about `common\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'due_date'], 'safe'],
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
    public function search($client, $params, $type = 'PYP')
    {
    	$business_id = $client->id;
        $query = Task::find();
        $count = \Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM task 
        		WHERE business_id=:business_id
        		AND type=:type
        		AND paid=:paid
        		AND deleted=:deleted
        		AND status_id=:status',[
        			':business_id' 	=> $business_id,
					':status'		=> 13,
        		])->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => parent::findInvoiceByUserId(),
        	'params' => [
		        ':userId' 	=> $client->user_id,
				':status'	=> 13
        	],
        	'totalCount' => $count,
        	'sort' => [
        		'attributes' => [
        		    'alt_business_name',
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
            'due_date' 		=> $this->due_date,
        ]);

        $query->andFilterWhere(['like', 'status', $this->type])
            ->andFilterWhere(['like', 'due_date', $this->comments]);

        return $dataProvider;
    }
} 