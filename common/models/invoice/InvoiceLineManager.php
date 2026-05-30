<?php

namespace common\models\invoice;

use common\models\TabularInputManager;

class InvoiceLineManager extends TabularInputManager
{
    protected $class = 'common\models\invoice\InvoiceLine';

    /**
     * Retrieve the list of Students
     * @return array of Student objects
     */
    public function getItems()
    {
        if (is_array($this->_items))
            return $this->_items;
        else {
            return [
                'n0' => new InvoiceLine,
            ];
        }
    }

    /**
     * Deletes the uneeded Students
     * @param $model Invoice - the parent model
     * @param $itemsPk array - an array of the primary keys of the child models which we want to keep
     */
    public function deleteOldItems($model, $itemsPk) 
    {
        InvoiceLine::deleteOldInvoiceLines($model, $itemsPk);
    }


    /**
     * Create a new TabularInputManager and loads the current child items
     * @param $model Invoice - the parent model
     * @return static the newly created TabularInputManager object
     */
    public function loadModel($model) 
    {
        //$return = new InvoiceLineManager;
        foreach($model->invoiceLines as $item)
            $this->_items[$item->primaryKey] = $item;

        return $this;
    }

    /**
     * Set the unsafe attributes for the child items, usually the primary key of the parent model
     * @param $item InvoiceLine - the child item
     * @param $model Invoice - the parent model
     */
    public function setUnsafeAttribute($item, $model) 
    {
        $item->invoice_id = $model->getPrimaryKey();
    }
}