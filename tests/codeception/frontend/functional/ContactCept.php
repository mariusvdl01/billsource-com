<?php
use tests\codeception\frontend\FunctionalTester;
use tests\codeception\frontend\_pages\ContactPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that contact works');

$contactPage = ContactPage::openBy($I);

$I->see('Billsource offers a range of products');

$I->amGoingTo('submit contact form with not correct email');
$subject = $I->grabTextFrom("descendant::select/descendant::*[@value = 'Debt counselling']");
$contactPage->submit([
    'name' => 'tester',
    'email' => 'tester.email',
    'subject' => $subject,
    'body' => 'test content',
    'verifyCode' => 'testme',
]);
$I->expectTo('see that email adress is wrong');
$I->dontSee('Name cannot be blank', '.help-block');
$I->see('Email is not a valid email address.');
$I->dontSee('Subject cannot be blank', '.help-block');
$I->dontSee('Body cannot be blank', '.help-block');
$I->dontSee('The verification code is incorrect', '.help-block');

$I->amGoingTo('submit contact form with correct data');
$subject = $I->grabTextFrom("descendant::select/descendant::*[@value = 'Rewards program']");
$contactPage->submit([
    'name' => 'tester',
    'email' => 'tester@example.com',
    'subject' => $subject,
    'body' => 'test content',
    'verifyCode' => 'testme',
]);
$I->see('Thank you for contacting us.');
