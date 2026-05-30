<?php

namespace common\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if userID matches client's ID passed via params
 */
class UserProfileRule extends Rule
{
    public $name = 'isUserProfile';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     *
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['client'])) {
            $model = $params['client'];
        } else {
            $model = Yii::$app->controller->getClient();
        }

        return ($user == $model->user_id);
    }
}