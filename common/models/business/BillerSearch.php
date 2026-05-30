<?php

namespace common\models\business;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\business\models\BusinessClientUser`.
 */
class BillerSearch extends User
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
     * @return ActiveDataProvider
     */
    public function search($user_id, $params)
    {
        $query = self::find();
        $count = Yii::$app->db->createCommand('
        	    (SELECT COUNT(*)
                FROM user u
                INNER JOIN business_client bc ON bc.user_id = u.user_id
                WHERE bc.parent_id = (SELECT c.business_id
                    FROM business_client c
                    INNER JOIN user e ON c.user_id = e.user_id
                    WHERE c.user_id =:id)
                AND is_biller = 1
                )', [
            ':id' => $user_id
        ])->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => BusinessClient::findBPOCustomers(),
            'params' => [
                ':id' => $user_id
            ],
            'totalCount' => $count,
            'sort' => [
                'attributes' => [
                    'trading_name',
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
            'user_id' => $this->user_id,
            'business_user' => $this->business_user,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);
        //$query->andFilterWhere(['like', 'item_name', $this->item_name]);

        return $dataProvider;
    }
}
