<?php

namespace common\models\invoice;

/**
 * This is the ActiveQuery class for [[InvoiceAgeType]].
 *
 * @see InvoiceAgeType
 */
class InvoiceAgeTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return InvoiceAgeType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return InvoiceAgeType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}