<?php

namespace common\models\invoice;

use common\models\TabularInputManager;

class TaskLineManager extends TabularInputManager
{
    protected $class = 'common\models\invoice\TaskLine';

    public function getItems()
    {
        if (is_array($this->_items))
            return $this->_items;
        else {
            return [
                'n0' => new TaskLine,
            ];
        }
    }

    /**
     * Deletes the uneeded Students
     * @param $model Task - the parent model
     * @param $itemsPk array - an array of the primary keys of the child models which we want to keep
     */
    public function deleteOldItems($model, $itemsPk) 
    {
        TaskLine::deleteOldTaskLines($model, $itemsPk);
    }


    /**
     * Create a new TabularInputManager and loads the current child items
     * @param $model Task - the parent model
     * @return static the newly created TabularInputManager object
     */
    public function loadModel($model)
    {
        //$return = new TaskLineManager;
       
        foreach($model->taskLines as $item)
            $this->_items[$item->primaryKey] = $item;

        return $this;
    }

    /**
     * Set the unsafe attributes for the child items, usually the primary key of the parent model
     */
    public function setUnsafeAttribute($item, $model) 
    {
        $item->taskId = $model->getPrimaryKey();
    }
}