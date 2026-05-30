<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/15/15
 * Time: 3:22 AM
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class UserRoleController extends Controller
{
    private $_authManager;


    public function getHelp()
    {

        $description = "DESCRIPTION\n";
        $description .= '    '."This command will add rules and it's data to authorize user actions.\n";
        return parent::getHelp() . $description;
    }

    /**
     * Add a user rule item
     *
     * @see \common\rbac\UserInRoleRule
     */
    public function actionInit()
    {
        $this->ensureAuthManagerDefined();
        $auth = $this->_authManager;

        //provide the oportunity for the user to abort the request
        $message = "This command will add rules and it's data to authorize user actions.\n";
        $message .= "Would you like to continue?";

        if($this->confirm($message))
        {
            // add the rule
            $rule = new \common\rbac\UserInRoleRule;
            $auth->add($rule);

            $roles = $auth->getRoles();
            foreach ($roles as $name => $role) {
                if($name == 'superUser')
                    continue;

                $role->ruleName = $rule->name;
                $auth->update($name, $role);
            }
            $this->stdout("Roles updated successfully\n", Console::FG_GREEN);
        }
    }

    protected function ensureAuthManagerDefined()
    {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if (($this->_authManager = Yii::$app->getAuthManager()) === null) {
            $message = "Error: an authorization manager, named 'authManager' must be con-figured to use this command.\n";
            $this->stderr($message, Console::FG_RED);
            exit;
        }
    }
}