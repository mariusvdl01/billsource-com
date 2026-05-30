<?php

namespace common\models\catalog;

use common\models\business\BusinessClient;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form about `common\models\catalog\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'business_id', 'quantity', 'active', 'out_of_stock', 'is_virtual'], 'integer'],
            [['ean_13', 'name', 'description', 'reference', 'condition'], 'safe'],
            [['cost_price', 'selling_price', 'width', 'height', 'depth', 'weight'], 'number'],
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
        $id = Yii::$app->session['__id'];
        $clientUser = BusinessClient::findOne(['user_id' => $id]);
        $query = Product::find()//->joinWith('catalogCategoryProducts', true, 'INNER JOIN')
        ->where([
            'business_id' => $clientUser->id,
        ]);
        //$query = new Query;
        //$query = $query->from(self::tableName() . ' a')
        //	->join('LEFT JOIN', 'catalog_category_product b', 'b.product_id = a.product_id')
        //	->where(['business_id' => $clientUser->business_id]);
        //var_dump($query->all());
        //exit;
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
            'id'           => $this->id,
            'business_id'  => $this->business_id,
            'cost_price'   => $this->cost_price,
            'selling_price' => $this->selling_price,
            'quantity'     => $this->quantity,
            'active'       => $this->active,
            'out_of_stock' => $this->out_of_stock,
            'width'        => $this->width,
            'height'       => $this->height,
            'depth'        => $this->depth,
            'weight'       => $this->weight,
            'is_virtual'   => $this->is_virtual,
        ]);

        $query->andFilterWhere(['like', 'ean_13', $this->ean_13])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'condition', $this->condition]);

        return $dataProvider;
    }
}
