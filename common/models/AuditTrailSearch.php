<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuditTrailSearch represents the model behind the search form about `common\models\AuditTrail`.
 */
class AuditTrailSearch extends AuditTrail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['audit_form', 'audit_action', 'audit_memo', 'ip_addr', 'created_at'], 'safe'],
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
    public function search($params)
    {
        $query = AuditTrail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'audit_form', $this->audit_form])
            ->andFilterWhere(['like', 'audit_action', $this->audit_action])
            ->andFilterWhere(['like', 'audit_memo', $this->audit_memo])
            ->andFilterWhere(['like', 'ip_addr', $this->ip_addr]);

        return $dataProvider;
    }
}
