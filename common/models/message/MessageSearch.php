<?php
/**
 * Created by PhpStorm.
 * User: kenny
 * Date: 6/3/17
 * Time: 1:07 PM
 */

namespace common\models\message;

use yii\data\ActiveDataProvider;

class MessageSearch extends \thyseus\message\models\MessageSearch
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Message::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        if($this->inbox)
            $query->andFilterWhere(['>=', 'status', null]);

        $query->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}