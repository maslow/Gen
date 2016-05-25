<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform log-in of dashboard');
$I->amOnPage(\yii\helpers\Url::to(['/dashboard/main/login']));
$I->see('Please fill the form to login.');

// '不填用户名直接登陆'
$I->fillField('LoginForm[verifyCode]','testme');
$I->fillField('LoginForm[password]','000000');
$I->click('Login');
$I->waitForText('用户名不能为空');
$I->wait(1);

// '不填密码直接登陆'
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[verifyCode]','testme');
$I->fillField('LoginForm[password]','');
$I->click('Login');
$I->waitForText('密码不能为空');
$I->wait(1);

// '不填错误验证码登陆'
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[password]','000000');
$I->fillField('LoginForm[verifyCode]','');
$I->click('Login');
$I->waitForText('验证码不正确');
$I->wait(1);

// '使用错误用户名登陆'
$I->fillField('LoginForm[username]', 'invalid');
$I->fillField('LoginForm[password]', '000000');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->waitForText('用户名是无效的');
$I->wait(1);

// '使用很短的用户名登陆'
$I->fillField('LoginForm[username]', 't');
$I->fillField('LoginForm[password]', '000000');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->waitForText('3个字符');
$I->wait(1);

// '使用很长的用户名登陆'
$I->fillField('LoginForm[username]', 'tttttttttttttttttttttttttttttttttttttttttttttttttttttt');
$I->fillField('LoginForm[password]', '000000');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->waitForText('32个字符');
$I->wait(1);

// '使用错误密码登陆'
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[password]', 'invalid');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->waitForText('用户名或密码不正确');
$I->wait(1);

// '使用很短的密码登陆'
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[password]', 'p');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->waitForText('至少');
$I->wait(1);

// '使用很长的密码登陆'
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[password]', 'ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp');
$I->fillField('LoginForm[verifyCode]','testme');
$I->click('Login');
$I->waitForText('至多');
$I->wait(1);

// '使用错误验证码登陆'
$I->fillField('LoginForm[username]', 'gen');
$I->fillField('LoginForm[password]','000000');
$I->fillField('LoginForm[verifyCode]','invalid');
$I->click('Login');
$I->waitForText('验证码不正确');
$I->wait(1);

// '正确填写表单登陆'
$I->fillField('LoginForm[verifyCode]','testme');
$I->wait(1);
$I->click('Login');
$I->waitForText('gen');
$I->seeInCurrentUrl('index');

// '退出登陆')
$I->click('gen');
$I->waitForText('退出登陆');
$I->click('退出登陆');
$I->wait(1);
