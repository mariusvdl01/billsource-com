<?php
use tests\codeception\frontend\FunctionalTester;
use tests\codeception\frontend\_pages\UserLoginPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure login page works');

$loginPage = UserLoginPage::openBy($I);

$I->amGoingTo('submit login form with no data');
$loginPage->login('', '');
$I->expectTo('see validations errors');
$I->see('email cannot be blank.');
$I->see('password cannot be blank.');

$I->amGoingTo('try to login with wrong credentials');
$I->expectTo('see validations errors');
$loginPage->login('onah.kenneth@gmail.com', 'wrong');
$I->expectTo('see validations errors');
$I->see('Incorrect email or password.', 'div.help-block');

$I->amGoingTo('try to login with correct credentials');
$loginPage->login('onah.kenneth@gmail.com', 'password_0');
$I->expectTo('see that user is logged');
$I->see('Dashboard');
$I->see('Tasks');
$I->dontSee('Login', 'button');
