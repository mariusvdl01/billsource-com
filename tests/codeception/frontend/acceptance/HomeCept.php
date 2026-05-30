<?php
use tests\codeception\frontend\AcceptanceTester;

/* @var $scenario Codeception\Scenario */
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->seeLink('Home');
$I->seeLink('Sign up');
$I->seeLink('Billers');
$I->seeLink('Individuals');
$I->seeLink('BPO');
$I->seeLink('VAS');
$I->seeLink('Counsellor');
$I->seeLink('Collector');
$I->seeLink('Contact us');
$I->click('Contact us');
$I->see('Billsource offers a range of products');
