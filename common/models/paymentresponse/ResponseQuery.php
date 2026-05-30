<?php

namespace common\models\paymentresponse;

/**
 * This is the ActiveQuery class for [[Response]].
 *
 * @see Response
 */
class ResponseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]] = 1');
        return $this;
    }*/

    /**
     * Inherited from [[ActiveQuery]]
     * 
     * @see \yii\db\ActiveQuery
     * @return Response[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Inherited from [[yii\db\ActiveQuery]]
     * 
     * @see \yii\db\ActiveQuery
     * @return Response|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}