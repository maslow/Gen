<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/17/16
 * Time: 12:26 AM
 */

return [
    // Mark: Specifications of this module
    'specifications' => [
        'version' => '1.0.0',
        'dependencies' => [
            'rbac',
            'auth'
        ],
    ],

    // Mark: ACL exported
    'ACL' => [
        'administrator' => [
            'view' => [
                'viewAdministrator',
                'viewSelf' => '\app\modules\admin\SelfRule'
            ],
            'update' => [
                'updateAdministrator',
                'updateSelf' => '\app\modules\admin\SelfRule'

            ],
            'index' => [
                'indexAdministrators'
            ],
            'delete' => [
                'deleteAdministrator' => '\app\modules\admin\NotDeleteSelfRule'
            ],
        ],
    ],

    // Mark: handlers
    'handlers' => [
        // the function will be called after installation of this module
        'afterInstall' => function () {
            // Create a Role that has all permissions
            $auth = \Yii::$app->authManager;
            $role = new yii\rbac\Role();
            $role->name = 'super-admin';
            $role->description = 'Super Admin';
            if (!$auth->getRole($role->name)) {
                $auth->add($role);
            } else {
                $role = $auth->getRole($role->name);
            }

            $permissions = $auth->getPermissions();
            foreach ($permissions as $p) {
                if (!$auth->hasChild($role, $p))
                    $auth->addChild($role, $p);
            }
            // Create an Administrator ,then assign the role created above to it

            $admin = new \app\modules\admin\models\Administrator();
            $admin->username = 'gen';
            $admin->password = '000000';

            if ($admin->save()) {
                $auth->assign($role, $admin->uid);
                return true;
            } else {
                return false;
            }
        },

        'afterRemove' => function () {
            $ulist = \app\modules\auth\models\U::findAll(['source' => \app\modules\admin\models\Administrator::U_SOURCE]);
            if (!$ulist)
                return true;
            foreach ($ulist as $u)
                $u->delete();

            return true;
        },
    ]
];
