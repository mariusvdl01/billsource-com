<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_category_product".
 *
 * @property integer $category_id
 * @property integer $product_id
 * @property integer $position
 *
 * @property CatalogCategory $category
 * @property CatalogProduct $product
 */
class CatalogCategoryProduct extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_category_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id', 'position'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('app', 'Category ID'),
            'product_id'  => Yii::t('app', 'Product ID'),
            'position'    => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CatalogCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['product_id' => 'product_id']);
    }
}
