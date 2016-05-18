<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use app\gen\Event;
use yii\base\Model;

class UpdateRoleForm extends Model
{
    public $name;
    public $description;
    public $data;
    public $permissions = [];


    public function rules()
    {
        return [
            [['name', 'description', 'permissions'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 16],
            ['description', 'string', 'min' => 3, 'max' => 16],
            ['data', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('dashboard', 'Role ID'),
            'description' => \Yii::t('dashboard', 'Title'),
            'data' => \Yii::t('dashboard', 'Remark'),
            'permissions' => \Yii::t('dashboard', 'Permissions'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $role = $this->getAuth()->getRole($this->name);
            if ($role) {
                $role->description = $this->description;
                $role->data = $this->data;

                $this->getAuth()->removeChildren($role);
                foreach ($this->permissions as $name) {
                    $p = $this->getAuth()->getPermission($name);
                    $this->getAuth()->addChild($role, $p);
                }
                $this->getAuth()->update($role->name, $role);
                Event::trigger(Module::className(), Module::EVENT_UPDATE_ROLE_SUCCESS, new Event());
                return true;
            } else {
                $this->addError('name', \Yii::t('dashboard', 'The role is not exist!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_UPDATE_ROLE_FAIL, new Event());
        return false;
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }
}