<?php
use tests\codeception\frontend\FunctionalTester;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->seeLink('Home');
$I->seeLink('Sign up');
$I->seeLink('Billers');
$I->seeLink('Individuals');
$I->seeLink('DCA');
//$I->seeLink('VAS');
$I->seeLink('Counsellor');
$I->seeLink('Collector');
$I->seeLink('Contact us');
$I->click('Contact us');
$I->see('Billsource offers a range of products');