<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use app\gen\Event;
use yii\base\Model;
use yii\rbac\Role;

class CreateRoleForm extends Model
{
    public $name;
    public $description;
    public $data;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
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
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $role = new Role();
            if (!$this->getAuth()->getRole($this->name)) {
                $role->name = $this->name;
                $role->description = $this->description;
                $role->data = $this->data;
                $this->getAuth()->add($role);
                Event::trigger(Module::className(), Module::EVENT_CREATE_ROLE_SUCCESS, new Event());
                return true;
            } else {
                $this->addError('name', \Yii::t('dashboard', 'The role name has already been exist!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_CREATE_ROLE_FAIL, new Event());
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