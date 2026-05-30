<?php

namespace common\models;

use Yii;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%reading}}".
 *
 * @property integer $id
 * @property string $image
 * @property string $description
 */
class Reading extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reading}}';
    }

    public static function findAllUtilities()
    {
        $query = self::find()->all();
        $data = ArrayHelper::map($query, 'id', 'description');

        return !isset($data) ? [] : $data;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['image', 'description'], 'string', 'max' => 128],
            [['description'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'  => Yii::t('app', 'Reading ID'),
            'image'       => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
