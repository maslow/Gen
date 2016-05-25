<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform log-in of dashboard');
$I->amOnPage(\yii\helpers\Url::to(['/dashboard/main/login']));

// '不填用户名直接登陆'
$I->see('Login');
$I->click('Login');
$I->waitForText('用户名不能为空');

$I->wantTo('不填密码直接登陆');
$I->fillField('LoginForm[username]', 'gen');
$I->click('Login');
$I->see('密码不能为空');

// '使用错误密码登陆'
$I->fillField('LoginForm[password]', 'invalid');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->wait(3);
$I->see('用户名或密码不正确');
$I->wait(1);

// '使用错误验证码登陆'
$I->fillField('LoginForm[verifyCode]','invalid');
$I->fillField('LoginForm[password]','000000');
$I->click('Login');
$I->wait(1);
$I->see('验证码不正确');

// '正确填写表单登陆'
$I->fillField('LoginForm[verifyCode]','testme');
$I->wait(1);
$I->click('Login');
$I->wait(3);

// '退出登陆')
$I->see('gen');
$I->click('gen');
$I->wait(1);
$I->see('退出登陆');
$I->click('退出登陆');
$I->wait(3);
