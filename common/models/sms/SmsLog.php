<?php

namespace common\models\sms;

use common\models\business\BusinessClient;
use common\models\invoice\Invoice;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sms_log".
 *
 * @property integer $business_id
 * @property string $period
 * @property string $count
 *
 * @property BusinessClient $business
 */
class SmsLog extends \common\models\BaseActiveRecord
{
    public static function replaceCount($cellNo, $bussId)
    {
        if (($data = self::getCounter($cellNo, $bussId)) !== false) {
            if (isset($data['business_id'], $data['period'], $data['count'])) {
                $sql = 'REPLACE INTO `' . self::tableName() . '`
    					(`business_id`, `period`, `count`)
    					VALUES (' . $data['business_id'] . ', ' . $data['period'] . ', ' . ($data['count'] + 1) . ')';

                return self::findBySql($sql)->createCommand()->execute();
            }
        }

        $log = new SmsLog;
        $log->business_id = $bussId;
        $log->period = date('Ym', time());
        $log->count = 1;

        return $log->save();
    }

    protected static function getCounter($cellNo, $bussId)
    {
        $counter = null;
        $where_clause = '1 = 1';
        $cell = '0' . substr($cellNo, -9);

        if ($bussId != 0)
            $where_clause = ' `b`.`id` = ' . $bussId;

        $sql = 'SELECT DISTINCT `b`.`id` AS `business_id`,
    			IFNULL(`a`.`period`, DATE_FORMAT(CURRENT_DATE(), \'%Y%m\'))
    			AS `period`, IFNULL(`a`.`count`, 0) AS `count`
    			FROM `business_client` `b`
    			INNER JOIN `business_profile` `c` ON `c`.`id` = `b`.`profile_id`
    			LEFT JOIN  (SELECT * FROM `' . self::tableName() . '`
    			WHERE `period` = DATE_FORMAT(CURRENT_DATE(), \'%Y%m\')) `a`
    			ON `a`.`business_id` = `b`.`id`
    			INNER JOIN (SELECT * FROM ' . Invoice::tableName() . '
    						WHERE `client_mobile` = \'' . $cell . '\') `d`
    						ON `d`.`business_id` = `b`.`id`
    			WHERE ' . $where_clause . '
    			ORDER BY `period` ASC, `business_id` ASC LIMIT 0, 1';

        $counter = self::findBySql($sql)->createCommand()->queryOne();

        return $counter === false ? false : $counter;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_log';
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'period', 'count'], 'required'],
            [['business_id', 'count'], 'integer'],
            [['period'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_id' => Yii::t('app', 'Business ID'),
            'period'      => Yii::t('app', 'Period'),
            'count'       => Yii::t('app', 'Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessClient::className(), ['id' => 'business_id']);
    }
}
