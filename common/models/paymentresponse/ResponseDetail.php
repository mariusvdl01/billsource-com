<?php

namespace common\models\paymentresponse;

use Yii;

/**
 * This is the model class for table "response_detail".
 *
 * @property integer $response_detail_id
 * @property integer $response_id
 * @property string $type
 * @property string $field
 * @property string $value
 *
 * @property Response $response
 */
class ResponseDetail extends \common\models\BaseActiveRecord
{
    /**
	 * Provides the name of the table
	 *
	 * @return string | the name of the table
	 */
    public static function tableName()
    {
        return 'response_detail';
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array | an array of validation rules
     */
    public function rules()
    {
        return [
            [['response_id', 'type', 'field', 'value'], 'required'],
            [['response_id'], 'integer'],
            [['type'], 'string', 'max' => 5],
            [['field'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 2048]
        ];
    }

    /**
     * Establish a one-to-many relationship with 'response' table
     *
     * @return array | an array of validation rules
     */
    public function getResponse()
    {
        return $this->hasOne(Response::className(), ['response_id' => 'response_id']);
    }

    /**
     * Creates an instance of [[ActiveQuery]] for a more customized way of running sql query
     *
     * @return yii\db\ActiveQuery an active query instance for performing sql query
     */
    public static function find()
    {
        return new ResponseQuery(get_called_class());
    }
}
