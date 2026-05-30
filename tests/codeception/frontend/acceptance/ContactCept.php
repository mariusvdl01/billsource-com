<?php
use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\ContactPage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that contact works');

$contactPage = ContactPage::openBy($I);

$I->see('Billsource offers a range of products');

$I->amGoingTo('submit contact form with no data');
$contactPage->submit([]);
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Billsource offers a range of products');
$I->see('Name cannot be blank', '.help-block');
$I->see('Email cannot be blank', '.help-block');
$I->see('Body cannot be blank', '.help-block');
$I->see('The verification code is incorrect', '.help-block');

$I->amGoingTo('submit contact form with incorrect email');
$subject = $I->grabTextFrom("descendant::select/descendant::*[@value = 'Log change request']");
$contactPage->submit([
    'name' => 'tester',
    'email' => 'tester@email',
    'subject' => $subject,
    'body' => 'test content',
    'verifyCode' => 'testme',
]);
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see that email adress is wrong');
$I->dontSee('Name cannot be blank', '.help-block');
$I->see('Email is not a valid email address.', '.help-block');
$I->dontSee('Body cannot be blank', '.help-block');
$I->dontSee('The verification code is incorrect', '.help-block');
$subject = $I->grabTextFrom("descendant::select/descendant::*[@value = 'Others']");
$I->amGoingTo('submit contact form with correct data');
$contactPage->submit([
    'name' => 'tester',
    'email' => 'tester@example.com',
    'subject' => $subject,
    'body' => 'test content',
    'verifyCode' => 'testme',
]);
if (method_exists($I, 'wait')) {
    $I->wait(5); // only for selenium
}
$I->see('For urgent response contact one of the numbers below.');
