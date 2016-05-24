<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that login page works');
$I->amOnPage(\yii\helpers\Url::to(['/dashboard/main/login']));
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[password]', '000000');
$I->fillField('LoginForm[verifyCode]', 'testme');
$I->click('Login');
$I->waitForText('gen');
$I->click('gen');
$I->see('Logout');
$I->wait(1);
$I->click('Logout');
$I->waitForText('Login');
$I->wait(3);