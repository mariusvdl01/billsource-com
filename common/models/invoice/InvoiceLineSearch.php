<?php

namespace common\models\invoice;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * InvoiceLineSearch represents the model behind the search form about `frontend\models\invoice\InvoiceLine`.
 */
class InvoiceLineSearch extends InvoiceLine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ 
					[ 
						['invoice_id', 'line_description', 'line_amount'], 'required'],
						[['invoice_id', 'line_progress_value', 'line_progress_maximum' ], 'integer' ],
						[['line_amount', 'line_qty', 'line_unit_price'], 'number'],
						[['line_description'], 'string', 'max' => 255] 
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
    public function search($invoice_id, $params, $type = 'INV')
    {
        $query = InvoiceLine::find()->indexBy($invoice_id); // where `id` is your primary key
 
				$dataProvider = new ActiveDataProvider([
    				'query' => $query,
				]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            $query->where('0 = 1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'line_amount' => $this->line_amount,
            'line_qty' => $this->line_qty,
            'line_unit_price' => $this->line_unit_price,
        	'line_progress_value' => $this->line_progress_value,
        	'line_progress_maximum' => $this->line_progress_maximum,
        ]);

        $query->andFilterWhere(['like', 'line_description', $this->line_description]);

        return $dataProvider;
    }
}
