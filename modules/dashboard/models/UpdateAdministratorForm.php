<?php

namespace app\modules\dashboard\models;

use app\modules\dashboard\Module;
use Yii;
use app\gen\Event;
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
        $event = new Event(['model' => $this]);
        if ($this->validate()) {
            /* @var Administrator $administrator */
            $administrator = Administrator::findOne($this->id);
            try {
                if ($this->password) {
                    $administrator->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                }
                $administrator->updated_at = time();

                if ($administrator->save()) {
                    $role = $this->getAuth()->getRole($this->role);
                    if (!$role) {
                        throw new \InvalidArgumentException(Yii::t('dashboard', 'The role is not exist!'));
                    }
                    $this->getAuth()->revokeAll($administrator->id);
                    $this->getAuth()->assign($role, $administrator->id);
                    Event::trigger(Module::className(), Module::EVENT_UPDATE_MANAGER_SUCCESS, $event);
                    return true;
                } else {
                    Yii::error($this->getErrors());
                }
            } catch (\Exception $e) {
                Yii::error("{$e->getMessage()} @{$e->getFile()}#Line{$e->getLine()}");
                $this->addError('username', Yii::t('dashboard','Throw an exception of saving data!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_UPDATE_MANAGER_FAIL, $event);
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