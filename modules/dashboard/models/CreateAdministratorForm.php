<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use Yii;
use app\gen\Event;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class CreateAdministratorForm
 * @package app\modules\dashboard\models
 */
class CreateAdministratorForm extends Model
{
    public $username;
    public $password;
    public $password_confirm;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'role', 'password', 'password_confirm'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            ['role', 'string', 'max' => 32],
            [['username'], 'unique', 'targetClass' => Administrator::className()],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
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
            'password_confirm' => Yii::t('dashboard', 'Confirm Password'),
            'role' => Yii::t('dashboard', 'Role'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        $event = new Event(['model' => $this]);
        if ($this->validate()) {
            $administrator = new Administrator();
            try {
                $administrator->username = $this->username;
                $administrator->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $administrator->created_at = $administrator->updated_at = time();
                $administrator->auth_key = Yii::$app->security->generateRandomString();
                $administrator->created_ip = Yii::$app->request->getUserIP();
                $administrator->created_by = Yii::$app->administrator->identity->id;
                $administrator->locked = 0;

                if (!($role = $this->getAuth()->getRole($this->role)))
                    throw new Exception("The role {$this->role} is not exist!");

                if ($administrator->save()) {
                    $this->getAuth()->assign($role, $administrator->id);
                    Event::trigger(Module::className(), Module::EVENT_CREATE_MANAGER_SUCCESS, $event);
                    return true;
                } else {
                    throw new Exception("Save administrator failed #{$administrator->getErrors()}");
                }
            } catch (\Exception $e) {
                Yii::error("{$e->getMessage()} @{$e->getFile()}#Line{$e->getLine()}");
                $this->addError('username', Yii::t('dashboard', 'Error'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_CREATE_MANAGER_FAIL, $event);
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