<?php

namespace common\models\invoice;

use common\models\catalog\Product;
use common\traits\ActiveRecordTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_line".
 */
class TaskLine extends ActiveRecord
{
    use ActiveRecordTrait;

    // public function __construct(array $config = [])
    // {
    //     parent::__construct($config);

    //     $this->openTaskId = null;
    // }

    /**
	 * @inheritdoc
	 */
	public static function tableName()
    {
		return '{{%task_line}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
    {
		return [
			[['taskId', 'openTaskId'], 'integer'],
		];
	}

	/**
	 *@inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => false,
				'updatedAtAttribute' => false,
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
    {
		return [
            'id' => Yii::t ( 'app', 'Task Line ID' ),
            'taskId' => Yii::t ( 'app', 'Task ID' ),
            'openTaskId' => Yii::t ( 'app', 'Open Task ID' )
		];
	}

    /**
     * @param $model
     * @param $itemsId
     * @throws \yii\db\Exception
     */
	public static function deleteOldTaskLines($model, $itemsId)
	{
		$query = self::findBySql(
			"DELETE FROM " . self::tableName()
			. " WHERE id NOT IN (" . implode(',', $itemsId)
			. ") AND taskId = " . $model->primaryKey
		);
		$query->createCommand()->execute();
	}
}
