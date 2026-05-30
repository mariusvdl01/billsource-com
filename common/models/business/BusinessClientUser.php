<?php

namespace common\models\business;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "business_client_user".
 *
 * @property integer $bc_user_id
 * @property integer $u_user_id
 * @property integer $business_id
 * @property integer $deleted
 *
 * @property BusinessClient $business
 * @property User $user
 */
class BusinessClientUser extends \common\models\BaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'business_client_user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['u_user_id', 'business_id'], 'required'],
			[['u_user_id', 'business_id', 'deleted'], 'integer'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'bc_user_id' => Yii::t('app', 'Bc User ID'),
			'u_user_id' => Yii::t('app', 'U User ID'),
			'business_id' => Yii::t('app', 'Business ID'),
			'deleted' => Yii::t('app', 'Deleted'),
		];
	}

	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => false,
				'updatedAtAttribute' => false,
			]
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBusiness()
	{
		return $this->hasOne(BusinessClient::className(), ['business_id' => 'business_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUUser()
	{
		return $this->hasOne(User::className(), ['user_id' => 'u_user_id']);
	}
}