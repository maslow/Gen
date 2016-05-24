<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Gen');
$I->wait(1);
$I->seeLink('Getting start!');
$I->click('Getting start!');

$I->see('Login');
$I->wait(3);