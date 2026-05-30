<?php

namespace common\models;

/**
 * This is the model class for table "{{%assistance}}".
 *
 * @property integer $id
 * @property integer $individual_id
 * @property string $total_outstanding
 * @property integer $agreed
 * @property integer $user_id
 *
 * @property IndividualClient $individual
 * @property User $user
 */
class Assistance extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%assistance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['individual_id', 'total_outstanding', 'user_id'], 'required'],
            [['individual_id', 'agreed', 'user_id'], 'integer'],
            [['total_outstanding'], 'number'],
            [['individual_id'], 'exist', 'skipOnError' => true, 'targetClass' => IndividualClient::className(), 'targetAttribute' => ['individual_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'individual_id' => 'Individual ID',
            'total_outstanding' => 'Total Outstanding',
            'agreed' => 'Agreed',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividual()
    {
        return $this->hasOne(IndividualClient::className(), ['id' => 'individual_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
