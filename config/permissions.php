<?php

return [
        /*
        |--------------------------------------------------------------------------
        | Application Permissions
        |--------------------------------------------------------------------------
        */

        // Dashboard Module
        'access backend' => [
                'display_name' => 'dashboard',
                'category'     => 'Dashboard',
        ],

        // Customer Module
        'view customer'  => [
                'display_name' => 'read',
                'category'     => 'Customer',
        ],

        'create customer' => [
                'display_name' => 'create',
                'category'     => 'Customer',
        ],

        'edit customer' => [
                'display_name' => 'update',
                'category'     => 'Customer',
        ],

        'delete customer' => [
                'display_name' => 'delete',
                'category'     => 'Customer',
        ],

        //chat
        'view_chat'       => [
                "display_name"  => 'read',
                "category"      => "chat",
                "default"       => true,
        ],
        'create_chat'       => [
                "display_name"  => 'create',
                "category"      => "chat",
                "default"       => true,
        ],
        'update_chat'       => [
                "display_name"  => 'update',
                "category"      => "chat",
                "default"       => true,
        ],

        'delete_chat'       => [
                "display_name"  => 'delete',
                "category"      => "chat",
                "default"       => true,
        ],

        //todos
        "view_todos"    => [
                "display_name"  => 'read',
                "category"      => "Todos",
                "default"       => true,
        ],
        "create_todos"    => [
                "display_name"  => 'create',
                "category"      => "Todos",
                "default"       => true,
        ],
        "update_todos"    => [
                "display_name"  => 'update',
                "category"      => "Todos",
                "default"       => true,
        ],

        "delete_todos"    => [
                "display_name"  => 'delete',
                "category"      => "Todos",
                "default"       => true,
        ],

        "can_assign_todos"    => [
                "display_name"  => 'Can assign',
                "category"      => "Todos",
                "default"       => true,
        ],

        //currencies
        'manage currencies' => [
                'display_name' => 'read',
                'category'     => 'Currencies',
        ],
        'create currencies' => [
                'display_name' => 'create',
                'category'     => 'Currencies',
        ],
        'edit currencies'   => [
                'display_name' => 'update',
                'category'     => 'Currencies',
        ],
        'delete currencies' => [
                'display_name' => 'delete',
                'category'     => 'Currencies',
        ],


        // Administrator Module

        'view administrator' => [
                'display_name' => 'read',
                'category'     => 'Administrator',
        ],

        'create administrator' => [
                'display_name' => 'create',
                'category'     => 'Administrator',
        ],

        'edit administrator' => [
                'display_name' => 'update',
                'category'     => 'Administrator',
        ],

        'delete administrator' => [
                'display_name' => 'delete',
                'category'     => 'Administrator',
        ],

        'view roles' => [
                'display_name' => 'read',
                'category'     => 'Admin Roles',
        ],

        'create roles' => [
                'display_name' => 'create',
                'category'     => 'Admin Roles',
        ],

        'edit roles' => [
                'display_name' => 'update',
                'category'     => 'Admin Roles',
        ],

        'delete roles' => [
                'display_name' => 'delete',
                'category'     => 'Admin Roles',
        ],


        //language module

        'view languages' => [
                'display_name' => 'read',
                'category'     => 'Language',
        ],

        'new languages' => [
                'display_name' => 'create',
                'category'     => 'Language',
        ],

        'manage languages' => [
                'display_name' => 'update',
                'category'     => 'Language',
        ],

        'delete languages' => [
                'display_name' => 'delete',
                'category'     => 'Language',
        ],

        // Settings Module

        'general settings' => [
                'display_name' => 'general',
                'category'     => 'Settings',
        ],

        'system_email settings' => [
                'display_name' => 'system_email',
                'category'     => 'Settings',
        ],

        'authentication settings' => [
                'display_name' => 'authentication',
                'category'     => 'Settings',
        ],

        'notifications settings' => [
                'display_name' => 'notifications',
                'category'     => 'Settings',
        ],

        'localization settings' => [
                'display_name' => 'localization',
                'category'     => 'Settings',
        ],

        'pusher settings' => [
                'display_name' => 'pusher',
                'category'     => 'Settings',
        ],


        'view background_jobs' => [
                'display_name' => 'background_jobs',
                'category'     => 'Settings',
        ],

        'view purchase_code' => [
                'display_name' => 'purchase_code',
                'category'     => 'Settings',
        ],

        'view payment_gateways' => [
                'display_name' => 'read',
                'category'     => 'Payment Gateways',
        ],

        'update payment_gateways' => [
                'display_name' => 'update',
                'category'     => 'Payment Gateways',
        ],

        'view email_templates' => [
                'display_name' => 'read',
                'category'     => 'Email Templates',
        ],

        'update email_templates' => [
                'display_name' => 'update',
                'category'     => 'Email Templates',
        ],

        'manage update_application' => [
                'display_name' => 'update_application',
                'category'     => 'Settings',
        ],
];
