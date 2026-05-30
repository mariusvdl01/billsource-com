<?php

namespace backend\models\clients;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\clients\BusinessClientCrm;

/**
 * BusinessClientCrmSearch represents the model behind the search form about `backend\models\clients\BusinessClientCrm`.
 */
class BusinessClientCrmSearch extends BusinessClientCrm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'business_id', 'province_id', 'uses', 'deleted', 'is_business'], 'integer'],
            [['id_number', 'email', 'trading_name', 'registration_number', 'registered_name', 'vat_reg_number',
                'phone_number', 'address_street', 'address_region', 'address_code', 'fax_number', 'first_name',
                'last_name', 'mobile', 'last_used', 'created_at', 'updated_at'], 'safe'],
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
        $query = BusinessClientCrm::find();

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
            'uses' => $this->uses,
            'last_used' => $this->last_used,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $this->deleted,
            'is_business' => $this->is_business,
        ]);

        $query->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'trading_name', $this->trading_name])
            ->andFilterWhere(['like', 'registration_number', $this->registration_number])
            ->andFilterWhere(['like', 'registered_name', $this->registered_name])
            ->andFilterWhere(['like', 'vat_reg_number', $this->vat_reg_number])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'address_street', $this->address_street])
            ->andFilterWhere(['like', 'address_region', $this->address_region])
            ->andFilterWhere(['like', 'address_code', $this->address_code])
            ->andFilterWhere(['like', 'fax_number', $this->fax_number])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);

        return $dataProvider;
    }
}
