<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use Yii;
use app\gen\Event;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class ResetPasswordForm
 * @package app\modules\dashboard\models
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('dashboard', 'Password'),
            'password_confirm' => Yii::t('dashboard', 'Confirm Password'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            /* @var $administrator Administrator */
            $administrator = Yii::$app->administrator->identity;
            try {
                $administrator->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $administrator->updated_at = time();
                $administrator->auth_key = Yii::$app->security->generateRandomString();

                if ($administrator->save()) {
                    Event::trigger(Module::className(), Module::EVENT_RESET_PASSWORD_SUCCESS);
                    return true;
                } else {
                    throw new Exception("Save administrator failed #{$administrator->getErrors()}");
                }
            } catch (\Exception $e) {
                Yii::error("{$e->getMessage()} @{$e->getFile()}#Line{$e->getLine()}");
                $this->addError('password', Yii::t('dashboard', 'Error!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_RESET_PASSWORD_FAIL);
        return false;
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return Yii::$app->authManager;
    }
}