<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/17/16
 * Time: 12:27 AM
 */

return [
    // Mark: Specifications of this module
    'specifications' => [
        'version' => '1.0.0',
        'dependencies' => [],
    ],

    // Mark: ACL exported
    'ACL' => [
        'role' => [
            'view' => [
                'View Role'
            ],
            'update' => [
                'Update Role'
            ],
            'index' => [
                'Browse All Roles'
            ],
            'delete' => [
                'Delete Role'
            ],
        ],
        'permission' => [
            'index' => [
                'Browse All Permissions'
            ]
        ],
    ],

    // Mark: handlers
    'handlers' => [
        'beforeInstall' => function () {
            return true;
        },

        // the function will be called after installation of this module
        'afterInstall' => function () {
            return true;
        },

        'beforeUpdate' => function () {
            return true;
        },

        'afterUpdate' => function () {
            return true;
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
