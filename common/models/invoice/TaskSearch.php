<?php

namespace common\models\invoice;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * TaskSearch represents the model behind the search form about `common\models\invoice\TaskSearch`.
 */
class TaskSearch extends Invoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'business_id'], 'integer'],
            [['client_id', 'client_email', 'client_mobile', 'reference_number', 'due_date', 'comments'], 'safe'],
            [['deleted'], 'boolean'],
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
        $count = Yii::$app->db->createCommand('
                    SELECT COUNT(*) FROM task 
                    WHERE business_id=:business_id
                    AND deleted=:deleted'
                    ,[
                        ':business_id' 	=> $business_id,
                        ':deleted'     	=> parent::NOT_DELETED,
                    ])->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => parent::findTaskByUserId(),
        	'params' => [
		        ':deleted'  => parent::NOT_DELETED,
		        ':userId' 	=> $client->user_id
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
            'deleted' 		=> $this->deleted,
            'due_date' 		=> $this->due_date,
        ]);

        $query->andFilterWhere(['like', 'alt_business_name', $this->alt_business_name])
            ->andFilterWhere(['like', 'client_id', $this->client_id])
            ->andFilterWhere(['like', 'client_email', $this->client_email])
            ->andFilterWhere(['like', 'client_mobile', $this->client_mobile])
            ->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}