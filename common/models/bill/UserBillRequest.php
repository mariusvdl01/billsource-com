<?php

namespace common\models\bill;

use common\helpers\ArrayHelper;
use common\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_bill_request".
 *
 * @property integer $id
 * @property integer $is_business_user
 * @property integer $user_id
 * @property array $request_id
 *
 * @property BillRequest $request
 * @property User $user
 */
class UserBillRequest extends \common\models\BaseActiveRecord
{
    /**
     *
     *
     * @var array $request_ids .
     */
    public $selectedRequestIds = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_bill_request}}';
    }

    /**
     * Validation rules to apply to class properties
     *
     * @return array array of validation rules
     */
    public function rules()
    {
        return [
            [['is_business_user', 'user_id', 'request_id'], 'safe'],
        ];
    }

    /**
     * Behaviors rules to initialize safe variables (such as created_at, updated_at)
     * prior to saving user's data.
     *
     * @return array array of behaviors
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
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(BillRequest::className(), ['id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     *
     * @param integer $user_id
     * @param integer $type
     *
     * @return array
     */
    public static function findAllBillRequestByType($user_id, $type)
    {
        $data = static::findBySql(
            'SELECT br.id, description, 
                (SELECT id 
                FROM user_bill_request ubr WHERE ubr.request_id = br.id 
                AND ubr.user_id IN (SELECT u.id FROM user u WHERE u.id = ' . $user_id . ')
    			LIMIT 0, 1) AS user_id FROM bill_request br WHERE type IN (0, ' . $type . ')
    			ORDER BY description ASC')
            ->createCommand()
            ->queryAll();

        return $data === false ? [] : $data;
    }

    public function saveBillRequest($user_id)
    {
        $rowCount = 0;
        $requestIds = $this->request_id;

        if (is_array($requestIds) && !empty($requestIds)) {
            foreach ($requestIds as $requestId) {
                if ('' === $this->findRequestId($requestId)) {
                    $rowCount = $this->insertBillRequest($requestId);
                }
                $rowCount = $this->deleteBillRequest($requestId, $requestIds);

            }
            if ($rowCount > 0)
                return true;
        }

        return true;
    }

    public function findAllUserRequestIds($user_id)
    {
        $selected = [];
        $data = static::getDb()->createCommand(
            'SELECT request_id
    				FROM ' . $this->tableName()
            . ' WHERE user_id = ' . $user_id
        )->queryAll();
        $selected = ArrayHelper::arrayValuesRecursive($data);

        return $data === false ? '' : $selected;
    }

    protected function findRequestId($request_id)
    {

        $data = static::getDb()->createCommand(
            'SELECT request_id
    				FROM ' . $this->tableName()
            . ' WHERE user_id = ' . $this->user_id
            . ' AND is_business_user = ' . $this->is_business_user
            . ' AND request_id = ' . $request_id
        )->queryOne();

        return $data === false ? '' : $data['request_id'];
    }

    protected function insertBillRequest($request_id)
    {
        return static::getDb()->createCommand()
            ->insert($this->tableName(), [
                'is_business_user' => $this->is_business_user,
                'user_id'          => $this->user_id,
                'request_id'       => $request_id,
            ])
            ->execute();
    }

    protected function deleteBillRequest($request_id, $post_request_ids)
    {
        $sql = 'DELETE FROM ' . $this->tableName()
            . ' WHERE is_business_user = ' . $this->is_business_user
            . ' AND user_id = ' . $this->user_id
            . ' AND NOT request_id IN (' . implode(', ', $post_request_ids) . ')';

        return static::getDb()->createCommand()->setSql($sql)->execute();
    }
}
