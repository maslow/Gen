<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use app\gen\Event;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\modules\dashboard\models
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            [['username'], 'exist', 'targetClass' => Administrator::className()],
            ['verifyCode', 'captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('dashboard', 'Username'),
            'password' => Yii::t('dashboard', 'Password'),
            'verifyCode' => Yii::t('dashboard', 'Captcha'),
        ];
    }

    /**
     * Login
     * @return bool
     */
    public function login()
    {
        $event = new Event(['model' => $this]);
        if ($this->validate()) {
            /* @var Administrator $admin */
            $admin = Administrator::findOne(['username' => $this->username]);
            try {
                if ($admin && Yii::$app->security->validatePassword($this->password, $admin->password_hash)) {
                    $this->getAdministrator()->login($admin);
                    Event::trigger(Module::className(), Module::EVENT_LOGIN_SUCCESS, $event);
                    return true;
                } else {
                    $this->addError('username', Yii::t('dashboard','Username and  password are incorrect!'));
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
                $this->addError('username', Yii::t('dashboard','The user has some exceptions!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_LOGIN_FAIL, $event);
        return false;
    }

    /**
     * Get Administrator User component
     * @return \yii\web\User
     */
    protected function getAdministrator()
    {
        return Yii::$app->administrator;
    }
}
