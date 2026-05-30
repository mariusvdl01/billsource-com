<?php

namespace common\models\payment;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "response_detail".
 *
 * @property integer $response_detail_id
 * @property integer $response_id
 * @property string $type
 * @property string $data
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
            [['response_id', 'type', 'data'], 'required'],
            [['response_id'], 'integer'],
        	[['type', 'data'], 'string'],
            [['type'], 'string', 'max' => 5],
        ];
    }

    public function behaviors()
    {
    	return [
    		[
    			'class' => TimestampBehavior::className(),
    			'updatedAtAttribute' => false,
    			'createdAtAttribute' => false,
    		]
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
