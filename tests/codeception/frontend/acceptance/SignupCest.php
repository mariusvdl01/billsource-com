<?php

namespace tests\codeception\frontend\acceptance;

use Yii;
use tests\codeception\frontend\_pages\SignupPage;
use common\models\User;

class SignupCest
{

    /**
     * This method is called before each cest class test method
     * @param \Codeception\Event\TestEvent $event
     */
    public function _before($event)
    {
        Yii::$app->db->createCommand('TRUNCATE TABLE auth_assignment')->execute();
    }

    /**
     * This method is called after each cest class test method, even if test failed.
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
        User::deleteAll(['email' => 'onapeter@yahoo.com']);
    }

    /**
     * This method is called when test fails.
     * @param \Codeception\Event\FailEvent $event
     */
    public function _fail($event)
    {
    }

    /**
     * @param \codeception_frontend\AcceptanceTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserSignupWithNoData($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->see('Please fill out the fields below');

        $I->amGoingTo('submit signup form with no data');

        $signupPage->submit([]);

        $I->expectTo('see validation errors');
        $I->canSee('Firstname cannot be blank.', 'div');
        $I->canSee('Lastname cannot be blank.', 'div.help-block');
        $I->canSee('Email cannot be blank.', 'div.help-block');
        $I->canSee('Password cannot be blank.', 'div.help-block');
        $I->canSee('Confirm Password cannot be blank.', 'div.help-block');
        $I->canSee('Category cannot be blank.', 'div.help-block');
        $I->canSee('Please accept Billsource Terms & Conditions.', 'div.help-block');
    }

    /**
     * @param \codeception_frontend\AcceptanceTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserSignupWithWrongEmail($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->see('Please fill out the fields below');

        $I->amGoingTo('submit signup form with incorrect email');
        $I->fillField(['name'=>"SignupForm[firstname]"], 'Kenneth');
        $I->fillField(['name'=>"SignupForm[lastname]"], 'Onah');
        $I->fillField(['name'=>"SignupForm[email]"], 'onah.kenneth@gmail');
        $I->fillField(['name'=>"SignupForm[password]"], 'gobluefin82');
        $I->fillField(['name'=>"SignupForm[confirmPassword]"], 'gobluefin82');
        $I->selectOption(['name'=>"SignupForm[category]"], '2');
        $I->checkOption('#signupform-tcs');
        $I->seeOptionIsSelected(['name'=>"SignupForm[category]"], '2');
        $I->click('Submit');
        $I->expectTo('see that email address is wrong');
        $I->canSee('Email is not a valid email address.', 'div');
    }

    public function testUserSignupWithExistingEmail($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->canSee('Please fill out the fields below');

        $I->amGoingTo('submit signup form with existing email');
        $I->fillField(['name'=>"SignupForm[firstname]"], 'Kenneth');
        $I->fillField(['name'=>"SignupForm[lastname]"], 'Onah');
        $I->fillField(['name'=>"SignupForm[email]"], 'onah.kenneth@gmail.com');
        $I->fillField(['name'=>"SignupForm[password]"], 'gobluefin82');
        $I->fillField(['name'=>"SignupForm[confirmPassword]"], 'gobluefin82');
        $I->selectOption(['name'=>"SignupForm[category]"], '2');
        $I->checkOption('#signupform-tcs');
        $I->seeOptionIsSelected(['name'=>"SignupForm[category]"], '2');
        $I->click('Submit');
        if (method_exists($I, 'wait')) {
            $I->wait(5); // only for selenium
        }
        
        $I->expectTo('see signup page');
        $I->canSee('This email address has already been taken', 'div');
    }

    public function testUserSignupWithCorrectEmail($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->canSee('Please fill out the fields below');

        $I->amGoingTo('submit signup form with correct email');
        $I->fillField(['name'=>"SignupForm[firstname]"], 'Peter');
        $I->fillField(['name'=>"SignupForm[lastname]"], 'Onah');
        $I->fillField(['name'=>"SignupForm[email]"], 'onapeter@yahoo.com');
        $I->fillField(['name'=>"SignupForm[password]"], 'gobluefin82');
        $I->fillField(['name'=>"SignupForm[confirmPassword]"], 'gobluefin82');
        $I->selectOption(['name'=>"SignupForm[category]"], '3');
        $I->checkOption('#signupform-tcs');
        $I->seeOptionIsSelected(['name'=>"SignupForm[category]"], '3');
        $I->click('Submit');
        if (method_exists($I, 'wait')) {
            $I->wait(5); // only for selenium
        }
        
        $I->expectTo('see login page');
        $I->canSee('Please fill out the following fields to login');
        $I->canSee('Email');
        $I->canSee('Password');
        $I->canSee('Login');
    }
}
