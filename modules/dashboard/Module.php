<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 2:59 PM
 */

namespace app\modules\dashboard;


class Module extends \yii\base\Module
{
    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_LOGIN_FAIL = 'loginFail';
    const EVENT_LOGIN_SUCCESS = 'loginSuccess';

    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    const EVENT_LOGIN_REQUIRED = 'loginRequired';

    const EVENT_PERMISSION_REQUIRED = 'permissionRequired';

    const EVENT_CREATE_MANAGER_SUCCESS = "createManagerSuccess";
    const EVENT_CREATE_MANAGER_FAIL = "createManagerFail";

    const EVENT_UPDATE_MANAGER_SUCCESS = "updateManagerSuccess";
    const EVENT_UPDATE_MANAGER_FAIL = "updateManagerFail";

    const EVENT_DELETE_MANAGER_SUCCESS = "deleteManagerSuccess";
    const EVENT_DELETE_MANAGER_FAIL = "deleteManagerFail";

    const EVENT_RESET_PASSWORD_SUCCESS = "resetPasswordSuccess";
    const EVENT_RESET_PASSWORD_FAIL = "resetPasswordSuccess";

    const EVENT_BEFORE_CREATE_ROLE = 'beforeCreateRole';
    const EVENT_CREATE_ROLE_SUCCESS = 'createRoleSuccess';
    const EVENT_CREATE_ROLE_FAIL ='createRoleFail';

    const EVENT_BEFORE_UPDATE_ROLE = 'beforeUpdateRole';
    const EVENT_UPDATE_ROLE_SUCCESS = 'updateRoleSuccess';
    const EVENT_UPDATE_ROLE_FAIL = 'updateRoleFail';

    const EVENT_BEFORE_DELETE_ROLE = 'beforeDeleteRole';
    const EVENT_DELETE_ROLE_SUCCESS= 'deleteRoleSuccess';
    const EVENT_DELETE_ROLE_FAIL = 'deleteRoleFail';

    public $defaultRoute = 'main';

    public function init()
    {
        parent::init();
        \Yii::$app->set('administrator', [
            'class' => '\yii\web\User',
            'identityClass' => 'app\modules\dashboard\models\Administrator',
            'enableAutoLogin' => false,
            'idParam' => "__{$this->id}__id",
            'identityCookie' => [
                'name' => "__{$this->id}__identity",
                'httpOnly' => true
            ],
        ]);
        \Yii::$app->i18n->translations['dashboard'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/dashboard/messages',
        ];
    }
}