<?php

namespace frontend\controllers\business;

class StatementController extends \common\controllers\StatementController
{
    /**
     * Defines how certain actions can be executed irrespective of the default behavior
     *
     * @return array $actions defines actions behavior
     */
    public function actions()
    {
        return parent::actions();
    }

    /**
     * Defines behaviors to attach to actions in this class.
     *
     * @return array $behaviors an array of behaviors
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actionCreditor()
    {
        return parent::actionCreditor();
    }

    public function actionDebtor()
    {
        return parent::actionDebtor();
    }
}