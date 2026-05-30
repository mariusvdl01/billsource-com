<?php

namespace common\models\invoice;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * InvoiceLogSearch represents the model behind the search form about `common\models\invoice\InvoiceLog`.
 */
class InvoiceLogSearch extends InvoiceLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'count'], 'integer'],
            [['period'], 'safe'],
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
    public function search($client, $params)
    {
        $query = InvoiceLog::find()->where('[[business_id]]=:id', [':id'=>$client->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'business_id' => $this->business_id,
            'count' => $this->count,
        ]);

        $query->andFilterWhere(['like', 'period', $this->period]);

        return $dataProvider;
    }
}
