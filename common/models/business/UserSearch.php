<?php

namespace common\models\business;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\business\models\BusinessClientUser`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules();
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
    public function search($user_id, $params)
    {
    	$parent_id = BusinessClient::findOne(['user_id' => $user_id])->parent_id;
        $query = self::find();
        $count = Yii::$app->db->createCommand('
        	    (SELECT COUNT(*) FROM user a
				INNER JOIN business_client b ON b.user_id = a.id
				WHERE (a.id =:userId
				AND is_biller = 0)
				OR b.parent_id =
					(SELECT f.id
					FROM business_client f
					INNER JOIN user e ON f.user_id = e.id
					WHERE f.user_id =:userId))', [
                    ':userId' => $user_id
                ])->queryScalar();
        		
        $dataProvider = new SqlDataProvider([
            'sql' => BusinessClient::findUsers(),
            'params' => [
                ':id' => $user_id
            ],
        	'totalCount' => $count,
        	'sort' => [
        		'attributes' => [
        			'email',
        			'item_name',
        		],
        	],
        	'pagination' => [
        		'pageSize' => 7,
        	]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'business_user' => $this->business_user,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);
        //$query->andFilterWhere(['like', 'item_name', $this->item_name]);

        return $dataProvider;
    }
}
