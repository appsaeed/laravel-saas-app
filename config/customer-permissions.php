<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Permissions
    |--------------------------------------------------------------------------
     */

    // Dashboard Module
    'access_backend' => [
        'display_name' => 'dashboard',
        'category' => 'Dashboard',
        'default' => true,
    ],

    //knowledge bases
    'developers' => [
        'display_name' => 'developers',
        'category' => 'Developers',
        'default' => true,
    ],

    //todos
    "view_todos" => [
        "display_name" => 'read',
        "category" => "todos",
        "default" => true,
    ],
    "create_todos" => [
        "display_name" => 'create',
        "category" => "todos",
        "default" => true,
    ],
    "update_todos" => [
        "display_name" => 'update',
        "category" => "todos",
        "default" => true,
    ],
    "delete_todos" => [
        "display_name" => 'delete',
        "category" => "todos",
        "default" => true,
    ],

    //chat
    'view_chat' => [
        "display_name" => 'read',
        "category" => "chat",
        "default" => true,
    ],
    'create_chat' => [
        "display_name" => 'create',
        "category" => "chat",
        "default" => true,
    ],
    'update_chat' => [
        "display_name" => 'update',
        "category" => "chat",
        "default" => true,
    ],

    'delete_chat' => [
        "display_name" => 'delete',
        "category" => "chat",
        "default" => true,
    ],

];
