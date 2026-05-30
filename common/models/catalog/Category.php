<?php

namespace common\models\catalog;

use common\models\business\BusinessClient;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Category
 * @package common\models\catalog
 *
 */
class Category extends \kartik\tree\models\Tree
{
    const CATEGORY_DISABLED = '1';
    const CATEGORY_ENABLED = '0';

    const CATEGORY_ACTIVE = '1';
    const CATEGORY_INACTIVE = '0';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_category}}';
    }

    public function initDefaults()
    {
        parent::initDefaults();
    }

    public static function findAllCategory($id)
    {
        $query = self::find()
            ->where('[[business_id]]=:id', [':id' => $id])
            ->all();
        $data = ArrayHelper::map($query, 'id', 'name');

        return !isset($data) ? [] : $data;
    }

    public static function findCategoryByBusinessId($id = 0)
    {
        if (!$id) {
            $id = Yii::$app->session['__id'];
            $bizClient = BusinessClient::findOne(['user_id' => $id]);
            $id = $bizClient->id;
        }

        $query = self::find();
        $data = $query->where('business_id=' . $id)
            ->andWhere('[[active]]=:active', [':active' => self::CATEGORY_ACTIVE])
            ->andWhere('[[disabled]]=:disabled', [':disabled' => self::CATEGORY_ENABLED])
            ->andWhere('lvl>=0');

        return $data;
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $user_id = Yii::$app->session['__id'];
            $business_id = BusinessClient::findOne(['user_id' => $user_id])->id;
            if($business_id) {
                $this->business_id = $business_id;
            }
            return true;
        } else {
            return false;
        }
    }

    /*
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $user_id = Yii::$app->user->identity->user_id;
            $clientUser = BusinessClient::findOne(['user_id' => $user_id]);
            $sql = 'INSERT INTO catalog_category_business
    				VALUES (' . $this->getPrimaryKey() . ','
                . $clientUser->business_id . ','
                . 'CURRENT_TIMESTAMP)';
            self::findBySql($sql)->createCommand()->execute();
        }
    }
    */
    public function beforeDelete()
    {
        $sql = '';
        if (parent::beforeDelete()) {

            return false;
        } else {
            return false;
        }
    }

    public static function findProductCategory($id)
    {
        $sql = 'SELECT category_id
    			FROM catalog_category_product
    			WHERE product_id =:product_id ';
        $data = self::findBySql($sql, [':product_id' => $id])->createCommand()->queryScalar();

        return $data === false ? '' : $data;
    }
}
