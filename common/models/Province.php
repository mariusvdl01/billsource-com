<?php

namespace common\models;

use common\helpers\ArrayHelper;
use common\models\individual\IndividualClient;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "province".
 *
 * @property integer $id
 * @property string $name
 *
 * @property IndividualClient[] $individualClients
 */
class Province extends BaseActiveRecord
{
    /**
     * Provides the name of the table
     *
     * @return string $tableName the name of the table
     */
    public static function tableName()
    {
        return '{{%province}}';
    }

    /**
     * Behaviors rules to initialize safe variables (such as created_at, updated_at)
     * prior to saving user's data.
     *
     * @return array $behaviors an array of behaviors
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array array of validation rules
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
        ];
    }

    /**
     * Customized attribute labels in rendered pages
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id'   => Yii::t('app', 'Province ID'),
            'name' => Yii::t('app', 'Province Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividualClients()
    {
        return $this->hasMany(IndividualClient::className(), ['province_id' => 'id']);
    }

    /**
     * @return array result mapped by id and name
     */
    public static function findAllProvinces()
    {
        $query = self::find()->all();
        $data = ArrayHelper::map($query, 'id', 'name');

        return !isset($data) ? [] : $data;
    }
}
