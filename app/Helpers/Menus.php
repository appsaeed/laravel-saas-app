<?php
namespace App\Helpers;

class Menus {

    /**
     * admin menus
     */
    public static function admin() {
        return [
            [
                "url" => route( 'admin.home' ),
                "name" => "Dashboard",
                "icon" => "home",
                "access" => "access backend",
            ],
            [
                "url" => route( 'admin.customers.index' ),
                "name" => "Users",
                "access" => "view customer",
                "icon" => "users",
            ],
            [
                "url" => "",
                "name" => "Administrator",
                "icon" => "user",
                "access" => "view administrator|view roles",
                "submenu" => [
                    [
                        "url" => route( 'admin.administrators.index' ),
                        "name" => "Administrators",
                        "access" => "view administrator",
                        "icon" => "users",
                    ],
                    [
                        "url" => route( 'admin.roles.index' ),
                        "name" => "Admin Roles",
                        "access" => "view roles",
                        "icon" => "user-check",
                    ],
                ],
            ],
            [
                "url" => "",
                "name" => "Settings",
                "icon" => "settings",
                "access" => "general settings|view languages|view payment_gateways|view email_templates|manage update_application",
                "submenu" => [
                    [
                        "url" => route( 'admin.settings.general' ),
                        "name" => "All Settings",
                        "access" => "general settings",
                        "icon" => "settings",
                    ],
                    [
                        "url" => route( 'admin.currencies.index' ),
                        "name" => "Countries",
                        "access" => "general settings",
                        "icon" => "map-pin",
                    ],
                    [
                        "url" => route( 'admin.languages.index' ),
                        "name" => "Language",
                        "access" => "view languages",
                        "icon" => "globe",
                    ],
                    [
                        "url" => route( 'admin.payment-gateways.index' ),
                        "name" => "Payment Gateways",
                        "access" => "view payment_gateways",
                        "icon" => "shopping-bag",
                    ],
                    [
                        "url" => route( 'admin.email-templates.index' ),
                        "name" => "Email Templates",
                        "access" => "view email_templates",
                        "icon" => "mail",
                    ],
                    [
                        "url" => url( config( 'app.admin_path' ) . "/update-application" ),
                        "name" => "Update Application",
                        "access" => "manage update_application",
                        "icon" => "upload",
                    ],
                ],
            ],
            [
                "url" => '',
                "name" => "Systems",
                "icon" => "terminal",
                "access" => "view_env|view_config|view_filemanager",
                "submenu" => [
                    [
                        "url" => route( 'admin.systems.environments' ),
                        "name" => "Environments",
                        "icon" => "terminal",
                        "access" => "view_env",
                    ],
                    [
                        "url" => route( 'admin.systems.config' ),
                        "name" => "App config",
                        "icon" => "settings",
                        "access" => "view_config",
                    ],
                    [
                        "url" => route( 'admin.systems.filemanager' ),
                        "name" => "Filemanager",
                        "icon" => "file",
                        "access" => "view_filemanager",
                    ],
                ],
            ],
            [
                "url" => route( 'admin.theme.customizer' ),
                "name" => "Theme Customizer",
                "icon" => "grid",
                "access" => "general settings",
            ],
            [
                "url" => "",
                "name" => "Tasks",
                "icon" => "file-text",
                "access" => "view_todos|create_todos|update_todos|delete_todos",
                "submenu" => [
                    [
                        "url" => route( 'admin.tasks.index' ),
                        "name" => __( 'All Tasks' ),
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => route( 'admin.tasks.myTasks' ),
                        "name" => __( 'MY Tasks' ),
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => route( 'admin.tasks.in_progress' ),
                        "name" => "In progress",
                        "access" => "view_todos",
                        "icon" => "circle",
                    ],
                    [
                        "url" => route( 'admin.tasks.reviews' ),
                        "name" => "Reviews",
                        "access" => "view_todos",
                        "icon" => "star",
                    ],
                    [
                        "url" => route( 'admin.tasks.complete' ),
                        "name" => "Complete",
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
                "url" => route( 'user.home' ),
                "name" => "Home",
                "icon" => "home",
                "access" => "access_backend",
            ],
            [
                "url" => "",
                "name" => "Tasks",
                "icon" => "file-text",
                "access" => "view_todos|create_todos|update_todos|delete_todos",
                "submenu" => [
                    [
                        "url" => route( 'customer.tasks.index' ),
                        "name" => "All tasks",
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => route( 'customer.tasks.mytasks' ),
                        "name" => "Your tasks",
                        "access" => "view_todos",
                        "icon" => "file-text",
                    ],
                    [
                        "url" => route( 'customer.tasks.receives' ),
                        "name" => "Receives",
                        "access" => "view_todos",
                        "icon" => "download",
                    ],
                    [
                        "url" => route( 'customer.tasks.in_progress' ),
                        "name" => "In progress",
                        "access" => "view_todos",
                        "icon" => "circle",
                    ],
                    [
                        "url" => route( 'customer.tasks.reviews' ),
                        "name" => "Reviews",
                        "access" => "view_todos",
                        "icon" => "star",
                    ],
                    [
                        "url" => route( 'customer.tasks.complete' ),
                        "name" => "Complete",
                        "access" => "view_todos",
                        "icon" => "check",
                    ],
                ],
            ],
            // [
            //     "url" => "",
            //     "name" => "Projects",
            //     "icon" => "file-text",
            //     "access" => "view_todos|create_todos|update_todos|delete_todos",
            //     "submenu" => [
            //         [
            //             "url" => route( 'customer.projects.index' ),
            //             "name" => "All projects",
            //             "access" => "view_todos",
            //             "icon" => "file-text",
            //         ],
            //         [
            //             "url" => route( 'customer.projects.mytasks' ),
            //             "name" => "Your projects",
            //             "access" => "view_todos",
            //             "icon" => "file-text",
            //         ],
            //         [
            //             "url" => route( 'customer.projects.receives' ),
            //             "name" => "Receives",
            //             "access" => "view_todos",
            //             "icon" => "download",
            //         ],
            //         [
            //             "url" => route( 'customer.projects.in_progress' ),
            //             "name" => "In progress",
            //             "access" => "view_todos",
            //             "icon" => "circle",
            //         ],
            //         [
            //             "url" => route( 'customer.projects.reviews' ),
            //             "name" => "Reviews",
            //             "access" => "view_todos",
            //             "icon" => "star",
            //         ],
            //         [
            //             "url" => route( 'customer.projects.complete' ),
            //             "name" => "Complete",
            //             "access" => "view_todos",
            //             "icon" => "check",
            //         ],
            //     ],
            // ],
            [
                "url" => route( 'customer.developer.settings' ),
                "name" => "Developers",
                "icon" => "terminal",
                "access" => "developers",
            ],
        ];
    }

    /**
     * application menu
     *
     * @return array[]
     */
    public static function data() {
        return (object) [
            "admin" => json_decode( json_encode( Menus::admin() ) ),
            "customer" => json_decode( json_encode( Menus::customer() ) ),
        ];
    }

    public static function isActive( $menu ): string {
        $url = $menu->url ?? '';
        return request()->url() === $url ? 'active' : '';
    }
}