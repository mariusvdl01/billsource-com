<?php

namespace common\models\business;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use common\models\business\SingleUserAdmin;
use common\models\business\BusinessClientUser;

/**
 * BCUserSearch represents the model behind the search form about `common\business\models\BusinessClientUser`.
 */
class BCUserSearch extends BusinessClientUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bc_user_id', 'u_user_id', 'business_id', 'deleted'], 'integer'],
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
    	$business_id = SingleUserAdmin::findOne(['user_id' => $user_id])
    					->business_id;
        $query = self::find();
        $count = Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM business_client_user
        		WHERE business_id=:business_id
        		AND deleted=:deleted',[
                	':business_id' 	=> $business_id,
                	':deleted' 		=> 0,
                ])->queryScalar();
        		
        $dataProvider = new SqlDataProvider([
            'sql' => SingleUserAdmin::getUsers($user_id),
        	'params' => [
        		':business_id' 	=> $business_id,
        		':deleted'		=> 0,
        	],
        	'totalCount' => $count + 1,
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
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'bc_user_id' => $this->bc_user_id,
            'u_user_id' => $this->u_user_id,
            'business_id' => $this->business_id,
            'deleted' => $this->deleted,
        ]);

        return $dataProvider;
    }
}
