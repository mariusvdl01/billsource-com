<?php

namespace common\models\business;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
- * BusinessEmployeeSearch represents the model behind the search form about `common\models\business\BusinessEmployee`.
- */
class BusinessEmployeeSearch extends BusinessEmployee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'business_id', 'province_id'], 'integer'],
            [['id_number', 'email', 'address_street', 'address_region', 'address_code', 'first_name', 'last_name',
                'mobile', 'created_at', 'updated_at'], 'safe'],
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
    public function search($id, $params)
    {
        $query = BusinessEmployee::find()->where('[[business_id]]=:id', [':id' => $id]);

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
            'is_active' => $this->is_active,
            'business_id' => $this->business_id,
            'province_id' => $this->province_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address_street', $this->address_street])
            ->andFilterWhere(['like', 'address_region', $this->address_region])
            ->andFilterWhere(['like', 'address_code', $this->address_code])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);

        return $dataProvider;
    }
}