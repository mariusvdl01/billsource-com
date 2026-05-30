<?php

namespace common\models\invoice;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * PayslipSearch represents the model behind the search form about `common\models\invoice\Payslip`.
 */
class PayslipSearch extends Invoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'business_id', 'paid', 'creditor'], 'integer'],
            [['type', 'alt_business_name', 'client_id', 'client_email', 'client_mobile', 
              'client_vat', 'reference_number', 'issue_date', 'due_date', 'comments', 'marketing', 'pdf'], 'safe'],
            [['deleted'], 'boolean'],
            [['discount', 'amount', 'vat', 'total'], 'number'],
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
     * @return SqlDataProvider
     */
    public function search($client, $params, $type = 'PYP')
    {
    	$business_id = $client->id;
        $query = Payslip::find();
        $count = Yii::$app->db->createCommand('
        	     SELECT COUNT(*) FROM ' . static::tableName() . ' 
        		WHERE business_id=:business_id
        		AND type=:type
        		AND paid=:paid
        		AND deleted=:deleted
        		AND status_id=:status',[
        			':business_id' 	=> $business_id,
        			':type' 	   	=> $type,
					':paid'			=> parent::INVOICE_PAID,
        			':deleted'     	=> parent::NOT_DELETED,
					':status'		=> parent::STATUS_SENT,
        		])->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => parent::findInvoiceByUserId(),
        	'params' => [
		        ':deleted'  => parent::NOT_DELETED,
        		':type'		=> $type,
				':paid'		=> parent::INVOICE_PAID,
		        ':userId' 	=> $client->user_id,
				':status'	=> parent::STATUS_SENT
        	],
        	'totalCount' => $count,
        	'sort' => [
        		'attributes' => [
        		    'alt_business_name',
        			'reference_number',
        			'due_date',
        		],
        	],
        	'pagination' => [
        		'pageSize' => 7,
        	]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            $query->where('0 = 1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' 	        => $this->id,
            'status_id' 	=> $this->status_id,
            'business_id' 	=> $this->business_id,
            'deleted' 		=> $this->deleted,
            'issue_date' 	=> $this->issue_date,
            'due_date' 		=> $this->due_date,
            'discount' 		=> $this->discount,
            'amount' 		=> $this->amount,
            'paid' 			=> $this->paid,
            'creditor' 		=> $this->creditor,
            'vat' 			=> $this->vat,
            'total' 		=> $this->total,
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