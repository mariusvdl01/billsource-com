<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\BaseActiveRecord;

/**
 * TabularInputManager is a utility class to manage tabular input.
 * it supplies all utility necessary for create, save models in tabular input
 */
abstract class TabularInputManager extends Model
{
    /**
     * The child items which we are working on.
     * @var array
     */
    protected $_items;
    /**
     * the class name of the child items
     * @var string
     */
    protected $class;
    /**
     * Holds the ID of the last record created
     * @var int
     */
    protected $_lastNew = 0;

    public function __construct(BaseActiveRecord $model, $config = [])
    {
        $this->loadModel($model);

        parent::__construct($config);
    }

    /**
     * Main function of the class.
     * Load the items from db and applies modifications
     *
     * @param BaseActiveRecord $parent - the parent model
     * @param $items_posted array - an array of the items submitted in $_POST
     *
     * @return void
     */
    public function manage(BaseActiveRecord $parent, $items_posted)
    {
        // Variable which will hold the last record created's ID
        $this->_lastNew = 0;
        $classname = $this->class;
        $this->_items = array();

        foreach ($items_posted as $i => $item_post) {
            // If this child is to be deleted, we jump to the next one.
            if (($i == 'command') || ($i == 'id'))
                continue;
            if (isset($items_posted['command']) && isset($items_posted["id"]))
                if (($items_posted['command'] == "delete") && ($items_posted["id"] == $i))
                    continue;

            // if the code is like 'nxxx', it is a new record
            if (substr($i, 0, 1) == 'n') {
                // Create a new record
                $item = new $classname();
                // Setting the unsafe attributes as soon as it is created (so it passes validation as well as save).
                $this->setUnsafeAttribute($item, $parent);
                // Remember the last object's id
                $this->_lastNew = substr($i, 1);
            } else // load from db
            {
                $pk = $i;
                $model = Yii::createObject($this->class);
                if (is_array($model->getPrimaryKey())) {
                    $pk = array();
                    foreach (array_keys($model->getPrimaryKey()) as $key) {
                        $pk[$key] = $item_post[$key];
                    }
                }
                $item = $model->findOne($pk);
            }
            $this->_items[$i] = $item;
            if (isset($items_posted[$i])) {
                foreach ($items_posted[$i] as $attribute => $value)
                    $item->setAttribute($attribute, $value);
            }
        }
        // Adding a new child
        if (isset($items_posted['command'])) {
            if ($items_posted['command'] == "add") {
                $newId = 'n' . ($this->_lastNew + 1);
                $item = new $classname();
                $this->_items[$newId] = $item;
            }
        }
    }

    public function getLastNew()
    {
        return $this->_lastNew;
    }

    /**
     * Retrieve the list of the child items
     * @return array the items loaded
     */
    public function getItems()
    {
        if (is_array($this->_items))
            return ($this->_items);
        else
            return array();
    }

    /**
     * Validates items
     * @return boolean whether validation was successful
     */
    public function validateLineItems()
    {
        $valid = true;
        /** @var $item BaseActiveRecord */
        foreach ($this->_items as $i => $item) {
            //we want to validate all tags, even if there are errors
            $valid = $item->validate() && $valid;
        }

        return $valid;
    }

    /**
     * Saves the items in the database, and deletes those items which are no longer needed.
     *
     * @param $parent BaseActiveRecord the parent object
     */
    public function saveLineItems($parent)
    {
        $itemsOk = array();
        // Add the new items
        foreach ($this->_items as $i => $item) {
            /** @var $item BaseActiveRecord */
            $this->setUnsafeAttribute($item, $parent);
            $item->save(false);
            $itemsOk[] = $item->primaryKey;
        }

        // Delete the old items
        if (!$parent->isNewRecord)
            $this->deleteOldItems($parent, $itemsOk);

        return true;
    }

    /**
     * Set the unsafe attributes for the child items, usually the primary key of the parent model
     *
     * @param $item BaseActiveRecord - the child item
     * @param $parent BaseActiveRecord - the parent model
     */
    public abstract function setUnsafeAttribute($item, $parent);

    /**
     * Deletes the old child items
     *
     * @param $parent BaseActiveRecord - the parent model
     * @param $itemsPk array - an array of the primary keys of the child models which we want to keep
     */

    public abstract function deleteOldItems($parent, $itemsPk);

    /**
     * Create a TabularInputManager and load the existent tags
     *
     * @param $parent BaseActiveRecord - the parent model
     *
     * @return TabularInputManager the newly created TabularInputManager
     */
    public abstract function loadModel($parent);
}