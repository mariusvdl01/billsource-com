<?php

namespace common\models\individual;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IndividualReadingSearch represents the model behind the search form about `frontend\models\individual\IndividualReading`.
 */
class IndividualReadingSearch extends IndividualReading
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'individual_id', 'read_id', 'invoice_line_id', 'reading_previous',
            'reading_current'], 'integer'],
            [['reading_month', 'created_at'], 'safe'],
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
    	$id = IndividualClient::findOne(['user_id'=>$user_id])->id;
        $query = self::find()->indexBy('id')
        			->joinWith('individual', true, 'INNER JOIN')
        			->joinWith('reading', true, 'INNER JOIN')
        			->where('[[individual_reading.individual_id]]=:id', [':id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'individual_id' => $this->individual_id,
            'read_id' => $this->read_id,
            'invoice_line_id' => $this->invoice_line_id,
            'reading_previous' => $this->reading_previous,
            'reading_current' => $this->reading_current,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'reading_month', $this->reading_month]);

        return $dataProvider;
    }
}
