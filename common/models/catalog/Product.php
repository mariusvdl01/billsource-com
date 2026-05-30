<?php

namespace common\models\catalog;

use common\helpers\ArrayHelper;
use common\models\business\BusinessClient;
use Yii;

/**
 * This is the model class for table "catalog_product".
 *
 * @property integer $id
 * @property integer $business_id
 * @property string $ean_13
 * @property string $name
 * @property string $description
 * @property string $reference
 * @property double $cost_price
 * @property double $selling_price
 * @property integer $quantity
 * @property integer $active
 * @property integer $out_of_stock
 * @property string $width
 * @property string $height
 * @property string $depth
 * @property string $weight
 * @property string $condition
 * @property integer $is_virtual
 *
 * @property CatalogCategoryProduct[] $catalogCategoryProducts
 */
class Product extends \common\models\BaseActiveRecord
{
    const PRODUCT_ACTIVE = '1';
    const PRODUCT_INACTIVE = '0';

    const PRODUCT_OUTOFSTOCK = '1';
    const PRODUCT_INSTOCK = '0';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'name', 'description', 'cost_price', 'selling_price', 'quantity'], 'required', 'message' => ''],
            [['description', 'condition'], 'string'],
            [['cost_price', 'selling_price', 'width', 'height', 'depth', 'weight'], 'number'],
            [['quantity', 'active', 'out_of_stock', 'is_virtual'], 'integer'],
            [['ean_13'], 'string', 'max' => 13],
            [['name'], 'string', 'max' => 255],
            [['reference'], 'string', 'max' => 32],
            [['ean_13'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ean_13'       => Yii::t('app', 'Ean 13'),
            'name'         => Yii::t('app', 'Name'),
            'description'  => Yii::t('app', 'Description'),
            'reference'    => Yii::t('app', 'Reference'),
            'cost_price'   => Yii::t('app', 'Cost Price'),
            'selling_price' => Yii::t('app', 'Selling Price'),
            'quantity'     => Yii::t('app', 'Quantity'),
            'active'       => Yii::t('app', 'Enabled'),
            'out_of_stock' => Yii::t('app', 'Out Of Stock'),
            'width'        => Yii::t('app', 'Width'),
            'height'       => Yii::t('app', 'Height'),
            'depth'        => Yii::t('app', 'Depth'),
            'weight'       => Yii::t('app', 'Weight'),
            'condition'    => Yii::t('app', 'Condition'),
            'is_virtual'   => Yii::t('app', 'Is Virtual'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $sql = '';
        $post = Yii::$app->request->post();
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $sql = 'INSERT INTO catalog_category_product (category_id, product_id)
    				VALUES(' . $post['category_id'] . ',' . $this->getPrimaryKey() . ')';
        } else {
            if (empty($post['hasEditable']) || !$post['hasEditable']) {
                $sql = 'UPDATE catalog_category_product SET category_id = ' . $post['category_id']
                    . ' WHERE product_id = ' . $this->id;
            }
        }
        self::findBySql($sql)->createCommand()->execute() !== false ? true : false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogCategoryProducts()
    {
        return $this->hasMany(CatalogCategoryProduct::className(), ['product_id' => 'id']);
    }

    public function findAvailableCategories($id)
    {
        $data = Category::findCategoryByBusinessId($id)->all();

        $data = ArrayHelper::map($data, 'id', 'name');

        return $data;
    }

    public static function findAvailableProduct($bizClient = null)
    {
        if (!$bizClient) {
            $id = Yii::$app->session['__id'];
            $bizClient = BusinessClient::findOne(['user_id' => $id]);
        }

        $query = self::find();
        $data = $query->where('business_id=' . $bizClient->id)
            ->andWhere('[[active]]=:active', [':active' => self::PRODUCT_ACTIVE])
            ->andWhere('[[out_of_stock]]=:oos', [':oos' => self::PRODUCT_INSTOCK])
            ->all();

        return ArrayHelper::map($data, 'name', 'name');
    }
}
