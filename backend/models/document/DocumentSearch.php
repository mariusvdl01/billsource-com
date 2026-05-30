<?php

namespace backend\models\document;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\document\Document;

/**
 * DocumentSearch represents the model behind the search form about `backend\models\document\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'business_id', 'paid', 'creditor'], 'integer'],
            [['type', 'alt_business_name', 'client_id', 'client_email', 'client_mobile', 'client_vat', 'reference_number', 'issue_date', 'due_date', 'comments', 'marketing', 'pdf'], 'safe'],
            [['deleted'], 'boolean'],
            [['discount', 'amount', 'subtotal', 'vat', 'total'], 'number'],
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
        $query = Document::find();

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
            'status_id' => $this->status_id,
            'business_id' => $this->business_id,
            'deleted' => $this->deleted,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'discount' => $this->discount,
            'amount' => $this->amount,
            'paid' => $this->paid,
            'creditor' => $this->creditor,
            'subtotal' => $this->subtotal,
            'vat' => $this->vat,
            'total' => $this->total,
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
