<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\helpers\ArrayHelper;
use common\models\individual\IndividualClient;

/**
 * This is the model class for table "title".
 *
 * @property integer $id
 * @property string $description
 *
 * @property IndividualClient[] $individualClients
 */
class Title extends BaseActiveRecord
{
    /**
     * Provides the name of the table
     *
     * @return string $tableName the name of the table
     */
    public static function tableName()
    {
        return '{{%title}}';
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
     * @return array $rules an array of validation rules
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 30],
            [['description'], 'unique'],
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
            'id'          => Yii::t('app', 'Title ID'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividualClients()
    {
        return $this->hasMany(IndividualClient::className(), ['title_id' => 'id']);
    }

    /**
     * @return array | [] 
     */
    public static function findAllTitles()
    {
        $query = self::find()->all();
        $data = ArrayHelper::map($query, 'id', 'description');

        return !isset($data) ? [] : $data;
    }
}
