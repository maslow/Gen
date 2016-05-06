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
        'deps' => [],
    ],

    // Mark: Permissions exported
    'permissions' => [
        'administrator.list' => [
            'name' => 'Administrator List',
            'description' => 'Determine whether you can browser the administrator list.'
        ],
        'administrator.update' => [
            'name' => 'Update Administrator',
            'description' => 'Something goes here.'
        ],
        'administrator.delete' => [
            'name' => 'Delete Administrator',
            'description' => 'Something goes here.'
        ]
    ],

    // Mark: Navigation configuration
    'navigation' => [
        'Administrators' => [
            'Administrator List' => [
                'url' => '/dashboard/administrator/list',
                'bind-permission' => [
                    'administrator.list',
                    'administrator.update',
                    'administrator.delete',
                ],
            ],
            'Create Administrator' => [
                'url' => '/dashboard/administrator/create',
                'bind-permission' => 'administrator.create',
            ],
            'Reset Password' => [
                'url' => '/dashboard/administrator/reset-password',
                'bind-permission' => 'administrator.reset-password',
            ],
            'Welcome Test Page' => '/dashboard/administrator/welcome'
        ]
    ],

    // Mark: handlers
    'handlers' => [
        // the init function will be called after installation of this module
        'init' => function () {
            return true;
        },

        // the beforeRemove function will be called before removing this module
        'beforeRemove' => function() {
            return true;
        }
    ]
];
