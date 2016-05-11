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
    ],

    // Mark: Navigation configuration
    'navigation' => [
        'Administrators' => [
            'Administrator List' => [
                'route' => 'administrator/list',
                'bind-permission' => [
                    'administrator.list',
                    'administrator.update',
                    'administrator.delete',
                ],
            ],
            'Create Administrator' => [
                'route' => 'administrator/create',
                'bind-permission' => 'administrator.create',
            ],
            'Reset Password' => [
                'route' => 'administrator/reset-password',
                'bind-permission' => 'administrator.reset-password',
            ]
        ],
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
