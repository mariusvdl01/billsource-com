<?php
use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\UserLoginPage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure login page works');

$loginPage = UserLoginPage::openBy($I);

$I->amGoingTo('submit login form with no data');
$loginPage->login('', '');
$I->expectTo('see validations errors');
$I->see('Email cannot be blank.', 'div');
$I->see('Password cannot be blank.', 'div');

$I->amGoingTo('try to login with wrong credentials');
$I->expectTo('see validations errors');
$loginPage->login('admin@example.com', 'wrong');
$I->expectTo('see validations errors');
$I->seeElement('div.help-block');

$I->amGoingTo('try to login with correct credentials');
$loginPage->login('onah.kenneth@gmail.com', 'password_0');
$I->expectTo('see to my dashbaoard');
//$I->canSee('Welcome (Kenneth Onah)', 'span');

// Uncomment if using WebDriver
$I->click('Logout');
$I->dontSeeLink('Logout');
$I->see('Login');
 
