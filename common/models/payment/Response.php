<?php

namespace common\models\payment;

use Yii;
use DateTime;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "response".
 *
 * @property integer $response_id
 * @property string $file
 * @property string $created_at
 *
 * @property ResponseDetail[] $responseDetails
 */
class Response extends \common\models\BaseActiveRecord
{
    /**
	 * Provides the name of the table
	 *
	 * @return string $tableName the name of the table
	 */
    public static function tableName()
    {
        return 'response';
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
    			'class' => TimestampBehavior::className(),
    			'updatedAtAttribute' => false,
    			'value' => function() {
    				$now = new DateTime();
    				return $now->format('Y-m-d H:i:s');
    			},
    		]
    	];
    }
    
    /**
     * Validation rules to apply to class properties
     *
     * @return array | an array of validation rules
     */
	public function rules()
    {
        return [
            [['file'], 'required'],
            [['created_at'], 'safe'],
            [['file'], 'string', 'max' => 30]
        ];
    }

    /**
     * Establish a one-to-many relationship with 'response_details' table
     *
     * @return array | an array of validation rules
     */
    public function getResponseDetails()
    {
        return $this->hasMany(ResponseDetail::className(), ['response_id' => 'response_id']);
    }

    /**
     * The ActiveQuery instance for this class
     *
     * @return yii\db\ActiveQuery an active query instance for performing sql query
     */
    public static function find()
    {
        return new ResponseQuery(get_called_class());
    }
}
