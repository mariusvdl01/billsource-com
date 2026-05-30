<?php

namespace tests\codeception\frontend\_pages;

use yii\codeception\BasePage;

/**
 * Represents loging page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester|\codeception_backend\AcceptanceTester|\codeception_backend\FunctionalTester $actor
 */
class UserLoginPage extends BasePage
{
    public $route = 'account/login';

    /**
     * @param string $email
     * @param string $password
     */
    public function login($email, $password)
    {
        $this->actor->fillField('input[name="UserLoginForm[email]"]', $email);
        $this->actor->fillField('input[name="UserLoginForm[password]"]', $password);
        $this->actor->click('Login');
    }
}