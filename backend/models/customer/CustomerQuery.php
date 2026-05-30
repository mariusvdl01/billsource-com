<?php

namespace backend\models\customer;

/**
 * This is the ActiveQuery class for [[Customer]].
 *
 * @see Customer
 */
class CustomerQuery extends \yii\db\ActiveQuery
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
     * @return Customer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Inherited from [[yii\db\ActiveQuery]]
     * 
     * @see \yii\db\ActiveQuery
     * @return Customer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}