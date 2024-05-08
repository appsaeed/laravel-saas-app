<?php
namespace App\Helpers;

class Menus {

    /**
     * admin menus
     */
    public static function admin() {
        return [
            [
                "url" => url( config( 'app.admin_path' ) . "/dashboard" ),
                'slug' => config( 'app.admin_path' ) . "/dashboard",
                "name" => "Dashboard",
                "i18n" => "Dashboard",
                "icon" => "home",
                "access" => "access backend",
            ],
            [
                "url" => url( config( 'app.admin_path' ) . "/users" ),
                'slug' => config( 'app.admin_path' ) . "/users",
                "name" => "Users",
                "i18n" => "Users",
                "access" => "view customer",
                "icon" => "users",
            ],
            [
                "url" => "",
                "name" => "Administrator",
                "i18n" => "Administrator",
                "icon" => "user",
                "access" => "view administrator|view roles",
                "submenu" => [
                    [
                        "url" => url( config( 'app.admin_path' ) . "/administrators" ),
                        'slug' => config( 'app.admin_path' ) . "/administrators",
                        "name" => "Administrators",
                        "i18n" => "Administrators",
                        "access" => "view administrator",
                        "icon" => "users",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/roles" ),
                        'slug' => config( 'app.admin_path' ) . "/roles",
                        "name" => "Admin Roles",
                        "i18n" => "Admin Roles",
                        "access" => "view roles",
                        "icon" => "user-check",
                    ],
                ],
            ],
            [
                "url" => "",
                "name" => "Settings",
                "i18n" => "Settings",
                "icon" => "settings",
                "access" => "general settings|view languages|view payment_gateways|view email_templates|manage update_application",
                "submenu" => [
                    [
                        "url" => url( config( 'app.admin_path' ) . "/settings" ),
                        'slug' => config( 'app.admin_path' ) . "/settings",
                        "name" => "All Settings",
                        "i18n" => "All Settings",
                        "access" => "general settings",
                        "icon" => "settings",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/countries" ),
                        'slug' => config( 'app.admin_path' ) . "/countries",
                        "name" => "Countries",
                        "i18n" => "Countries",
                        "access" => "general settings",
                        "icon" => "map-pin",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/languages" ),
                        'slug' => config( 'app.admin_path' ) . "/languages",
                        "name" => "Language",
                        "i18n" => "Language",
                        "access" => "view languages",
                        "icon" => "globe",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/payment-gateways" ),
                        'slug' => config( 'app.admin_path' ) . "/payment-gateways",
                        "name" => "Payment Gateways",
                        "i18n" => "Payment Gateways",
                        "access" => "view payment_gateways",
                        "icon" => "shopping-bag",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/email-templates" ),
                        'slug' => config( 'app.admin_path' ) . "/email-templates",
                        "name" => "Email Templates",
                        "i18n" => "Email Templates",
                        "access" => "view email_templates",
                        "icon" => "mail",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/update-application" ),
                        'slug' => config( 'app.admin_path' ) . "/update-application",
                        "name" => "Update Application",
                        "i18n" => "Update Application",
                        "access" => "manage update_application",
                        "icon" => "upload",
                    ],
                ],
            ],
            [
                "url" => url( config( 'app.admin_path' ) . "/customizer" ),
                'slug' => config( 'app.admin_path' ) . "/customizer",
                "name" => "Theme Customizer",
                "i18n" => "Theme Customizer",
                "icon" => "grid",
                "access" => "general settings",
            ],
            [
                "url" => "",
                "name" => "Todos",
                "i18n" => "todos",
                "icon" => "file-text",
                "access" => "view_todos|create_todos|update_todos|delete_todos",
                "submenu" => [

                    [
                        "url" => url( config( 'app.admin_path' ) . '/todos/all' ),
                        'slug' => config( 'app.admin_path' ) . '/todos/all',
                        "name" => "all",
                        "i18n" => "all",
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . '/todos/created' ),
                        'slug' => config( 'app.admin_path' ) . '/todos/created',
                        "name" => "created",
                        "i18n" => "created",
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . '/todos/in-progress' ),
                        'slug' => config( 'app.admin_path' ) . '/todos/in-progress',
                        "name" => "In progress",
                        "i18n" => "in-progress",
                        "access" => "view_todos",
                        "icon" => "circle",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . '/todos/reviews' ),
                        'slug' => config( 'app.admin_path' ) . '/todos/reviews',
                        "name" => "review",
                        "i18n" => "reviews",
                        "access" => "view_todos",
                        "icon" => "star",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . '/todos/complete' ),
                        'slug' => config( 'app.admin_path' ) . '/todos/complete',
                        "name" => "complete",
                        "i18n" => "complete",
                        "access" => "view_todos",
                        "icon" => "check",
                    ],
                ],
            ],
        ];
    }

    /**
     * customer menus
     */
    public static function customer() {
        return [
            [
                "url" => url( "dashboard" ),
                'slug' => "dashboard",
                "name" => "Home",
                "i18n" => "Dashboard",
                "icon" => "home",
                "access" => "access_backend",
            ],
            [
                "url" => "",
                "name" => "Todos",
                "i18n" => "todos",
                "icon" => "file-text",
                "access" => "view_todos|create_todos|update_todos|delete_todos",
                "submenu" => [
                    [
                        "url" => url( 'todos/created' ),
                        'slug' => 'created',
                        "name" => "created",
                        "i18n" => "created",
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => url( 'todos/receives' ),
                        'slug' => 'receives',
                        "name" => "received",
                        "i18n" => "receives",
                        "access" => "view_todos",
                        "icon" => "download",
                    ],
                    [
                        "url" => url( 'todos/in-progress' ),
                        'slug' => 'in-progress',
                        "name" => "In progress",
                        "i18n" => "in-progress",
                        "access" => "view_todos",
                        "icon" => "circle",
                    ],
                    [
                        "url" => url( 'todos/reviews' ),
                        'slug' => 'reviews',
                        "name" => "review",
                        "i18n" => "reviews",
                        "access" => "view_todos",
                        "icon" => "star",
                    ],
                    [
                        "url" => url( 'todos/complete' ),
                        'slug' => 'complete',
                        "name" => "complete",
                        "i18n" => "complete",
                        "access" => "view_todos",
                        "icon" => "check",
                    ],
                ],
            ],
            [
                "url" => url( "developers" ),
                'slug' => "developers",
                "name" => "Developers",
                "i18n" => "Developers",
                "icon" => "terminal",
                "access" => "developers",
            ],
        ];
    }
}