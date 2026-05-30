<?php

namespace tests\codeception\frontend\functional;

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
        //Yii::$app->db->createCommand('TRUNCATE TABLE auth_assignment')->execute();
    }

    /**
     * This method is called after each cest class test method, even if test failed.
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
        User::deleteAll(['email' => 'tester.email@example.com']);
    }

    /**
     * This method is called when test fails.
     * @param \Codeception\Event\FailEvent $event
     */
    public function _fail($event)
    {
    }

    /**
     *
     * @param \codeception_frontend\FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserSignup($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->see('Please fill out the fields below');
		
        $I->amGoingTo('submit signup form with no data');

        $signupPage->submit([]);

        $I->expectTo('see validation errors');
        $I->see('Firstname cannot be blank.', '.help-block');
        $I->see('Lastname cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');
        $I->see('Confirm Password cannot be blank.', '.help-block');
        $I->see('Please choose a category', '.help-block');
        $I->see('Please accept Billsource Terms & Conditions.', '.help-block');

        $I->amGoingTo('submit signup form with not correct email');
        $signupPage->submit([
            'firstname' => 'Kenneth',
            'lastname' => 'Onah',
            'email' => 'tester.email',
            'password' => 'tester_password',
            'confirmPassword' => 'tester_password',
            'category' => '3',
            'tcs' => true,
        ]);

        $I->expectTo('see that email address is wrong');
        $I->dontSee('Firstname cannot be blank.', '.help-block');
        $I->dontSee('Lastname cannot be blank.', '.help-block');
        $I->dontSee('Email cannot be blank.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
        $I->dontSee('Confirm Password cannot be blank.', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');

        $I->amGoingTo('submit signup form with correct email');
        $signupPage->submit([
            'firstname' => 'Kenneth',
            'lastname' => 'Onah',
            'email' => 'tester.email@example.com',
            'password' => 'tester_password',
            'confirmPassword' => 'tester_password',
            'category' => '2',
            'tcs' => true,
        ]);

        $I->expectTo('see that user is created');
        $I->seeRecord('common\models\User', [
            'email' => 'tester.email@example.com',
        ]);
    }
}
