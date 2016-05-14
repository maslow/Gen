<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use Yii;
use app\gen\Event;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class UpdateAdministratorForm
 * @package app\modules\dashboard\models
 */
class UpdateAdministratorForm extends Model
{
    public $id;
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
            [['id', 'username', 'role'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            ['role', 'string', 'max' => 32],
            [['username'], 'exist', 'targetClass' => Administrator::className()],
            [['id'], 'exist', 'targetClass' => Administrator::className()],
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
        if ($this->validate()) {
            /* @var Administrator $administrator */
            $administrator = Administrator::findOne($this->id);
            try {
                if ($this->password)
                    $administrator->password_hash = Yii::$app->security->generatePasswordHash($this->password);

                $administrator->updated_at = time();

                if (!($role = $this->getAuth()->getRole($this->role)))
                    throw new Exception("The role {$this->role} is not exist!");

                if ($administrator->save()) {
                    $this->getAuth()->revokeAll($administrator->id);
                    $this->getAuth()->assign($role, $administrator->id);
                    Event::trigger(Module::className(), Module::EVENT_UPDATE_MANAGER_SUCCESS);
                    return true;
                } else {
                    throw new Exception("Save administrator failed #{$administrator->getErrors()}");
                }
            } catch (\Exception $e) {
                Yii::error("{$e->getMessage()} @{$e->getFile()}#Line{$e->getLine()}");
                $this->addError('username', Yii::t('dashboard', 'Error!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_UPDATE_MANAGER_FAIL);
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