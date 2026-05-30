<?php

namespace common\models\bill;

/**
 * This is the model class for table "bill_request".
 *
 * @property integer $id
 * @property integer $type
 * @property string $description
 *
 * @property UserBillRequest[] $userBillRequests
 */
class BillRequest extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bill_request}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findAllBillRequests()
    {
        return self::find()->all();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'description'], 'required'],
            [['type'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'  => 'Request ID',
            'type'        => 'Type',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBillRequests()
    {
        return $this->hasMany(UserBillRequest::className(), ['request_id' => 'id']);
    }

    public function findBillRequestsByType($type)
    {
        return self::findAll(['type' => $type]);
    }
}
