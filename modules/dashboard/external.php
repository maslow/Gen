<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 3:00 PM
 */

return [
    'id' => 'dashboard',

    // Mark: Specifications of this module
    'specifications' => [
        'version' => '1.0.0',
        'description' => 'Dashboard module of Gen.',
        'bootstrap' => true,
        'dependencies' => [],
    ],

    // Mark: Permissions exported
    'permissions' => [
        'administrator' => [
            'list' => 'Browse Administrators',
            'update' => 'Update Administrator',
            'delete' => 'Delete Administrator',
            'create' => 'Create Administrator',
            'reset-password' => 'Reset Password',
        ],
        'role' => [
            'list' => 'Browse Roles',
            'update' => 'Update Role',
            'delete' => 'Delete Role',
            'create' => 'Create Role',
        ]
    ],

    // Mark: Navigation configuration
    'navigation' => [
        'Administrators' => [
            'Administrator List' => [
                'route' => 'dashboard-administrator/list',
                'bind-permission' => [
                    'administrator.list',
                    'administrator.update',
                    'administrator.delete',
                ],
            ],
            'Create Administrator' => [
                'route' => 'dashboard-administrator/create',
                'bind-permission' => 'administrator.create',
            ],
            'Reset Password' => [
                'route' => 'dashboard-administrator/reset-password',
                'bind-permission' => 'administrator.reset-password',
            ],
        ],
        'Roles' => [
            'Role List' => [
                'route' => 'dashboard-role/list',
                'bind-permission' => [
                    'role.list',
                    'role.update',
                    'role.delete'
                ]
            ],
            'Create Role' => [
                'route' => 'dashboard-role/create',
                'bind-permission' => 'administrator.create'
            ]
        ]
    ],

    // Mark: handlers
    'handlers' => [
        'beforeInstall' => function () {
            return true;
        },
        // the function will be called after installation of this module
        'afterInstall' => function () {
            // Create a Role that has all permissions
            $auth = \Yii::$app->authManager;
            $role = new yii\rbac\Role();
            if (!$auth->getRole($role->name)) {
                $role->name = 'super-admin';
                $role->description = 'Super Admin';
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
            $admin = new \app\modules\dashboard\models\Administrator();
            $admin->username = 'gen';
            $admin->password_hash = Yii::$app->security->generatePasswordHash('000000');
            $admin->auth_key = Yii::$app->security->generateRandomString();
            $admin->locked = 0;
            $admin->updated_at = time();
            $admin->created_at = time();
            $admin->created_by = 1;
            $admin->created_ip = "127.0.0.1";
            if ($admin->save()) {
                $auth->assign($role, $admin->id);
                return true;
            }
            return false;
        },

        'beforeUpdate' => function () {
            return true;
        },
        'afterUpdate' => function () {
            if (\app\modules\dashboard\models\Administrator::find()->exists()) {
                return true;
            }
            // Create a Role that has all permissions
            $auth = \Yii::$app->authManager;

            $role = new yii\rbac\Role();
            if (!$auth->getRole($role->name)) {
                $role->name = 'super-admin';
                $role->description = 'Super Admin';
                $auth->add($role);
            } else {
                $role = $auth->getRole($role->name);
            }

            $permissions = $auth->getPermissions();
            foreach ($permissions as $p) {
                if (!$auth->hasChild($role, $p))
                    $auth->addChild($role, $p);
            }

            $admin = new \app\modules\dashboard\models\Administrator();
            $admin->username = 'gen';
            $admin->password_hash = Yii::$app->security->generatePasswordHash('000000');
            $admin->auth_key = Yii::$app->security->generateRandomString();
            $admin->locked = 0;
            $admin->updated_at = time();
            $admin->created_at = time();
            $admin->created_by = 0;
            $admin->created_ip = "127.0.0.1";
            if ($admin->save()) {
                $auth->removeAllAssignments();
                $auth->assign($role, $admin->id);
                return true;
            }
            return false;
        },

        // the beforeRemove function will be called before removing this module
        'beforeRemove' => function () {
            return true;
        },

        'afterRemove' => function () {
            return true;
        }
    ]
];
