<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'AdminLTE 3',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => env('APP_NAME'),
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#661-authentication-views-classes
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#662-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#69-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => '',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#610-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-menu
    |
    */

    'menu' => [
        [
            'text' => 'search',
            'search' => true,
            'topnav' => true,
        ],
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],
        ['header' => 'account_settings'],
        [
            'text'        => 'Users',
            'url'         => 'dashboard/user',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-user',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add User',
                    'url'  => 'dashboard/user/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show User',
                    'url'  => 'dashboard/user',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Admins',
            'url'         => 'dashboard/admin',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-user',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Admin',
                    'url'  => 'dashboard/admin/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show Admin',
                    'url'  => 'dashboard/admin',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            // 'text'        => 'Steamers',
            'text'        => 'الوكلات',
            // 'url'         => 'dashboard/streamer',
            'roles'        => ['admin', 'super_admin', 'streamer_admin'],
            'icon'        => 'fa fa-fw fa-user',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'المضيفين',
                    'url'  => 'dashboard/user/streamers',
                    'icon'  => 'fa fa-eye'

                ],
                [
                    'text' => 'الوكلاء',
                    'url'  => 'dashboard/streameradmin',
                    'icon'  => 'fa fa-eye'

                ],
                [
                    'text' => 'التقارير',
                    'url'  => 'dashboard/streameradminreporting',
                    'icon'  => 'fa fa-eye'

                ],
                [
                    'text' => 'التارجت',
                    'url'  => 'dashboard/targets',
                    'icon'  => 'fa fa-eye'

                ]
                // [
                //     'text' => 'Add Streamer',
                //     'url'  => 'dashboard/streamer/create',
                //     'icon'  => 'fa fa-plus'
                // ],

            ]
        ],
        // [
        //     'text'        => 'الوكلاء',
        //     'url'         => 'dashboard/streameradmin',
        //     'roles'        => ['admin', 'super_admin'],
        //     'can'          => 'full-access',
        //     'icon'        => 'fa fa-fw fa-user',
        //     // 'label'       => 4,
        //     'label_color' => 'success',
        //     'submenu'     =>  [
        //         [
        //             'text' => 'Add Streamer Admin',
        //             'url'  => 'dashboard/streameradmin/create',
        //             'icon'  => 'fa fa-plus'

        //         ],
        //         [
        //             'text' => 'Show Streamer Admins',
        //             'url'  => 'dashboard/streameradmin',
        //             'icon'  => 'fa fa-eye'

        //         ]
        //     ]
        // ],
        // [
        //     'text'        => 'Steamers Reporting',
        //     'url'         => 'dashboard/streameradminreporting',
        //     'roles'        => ['admin', 'super_admin'],
        //     'can'          => 'full-access',
        //     'icon'        => 'fa fa-fw fa-user',
        //     // 'label'       => 4,
        //     'label_color' => 'success',
        //     'submenu'     =>  [
        //         [
        //             'text' => 'Show Streamers Reporting',
        //             'url'  => 'dashboard/streameradminreporting',
        //             'icon'  => 'fa fa-eye'

        //         ]
        //     ]
        // ],
        // [
        //     'text'        => 'Targets',
        //     'url'         => 'dashboard/targets',
        //     'roles'        => ['admin', 'super_admin'],
        //     'can'          => 'full-access',
        //     'icon'        => 'fa fa-fw fa-tags',
        //     // 'label'       => 4,
        //     'label_color' => 'success',
        //     'submenu'     =>  [
        //         [
        //             'text' => 'Add Targets',
        //             'url'  => 'dashboard/targets/create',
        //             'icon'  => 'fa fa-plus'

        //         ],

        //         [
        //             'text' => 'Show Targets',
        //             'url'  => 'dashboard/targets',
        //             'icon'  => 'fa fa-eye'

        //         ]
        //     ]
        // ],
        [
            'text'        => 'Currnecy Worth',
            'url'         => 'dashboard/currency_worth',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Edit Currnecy Worth',
                    'url'  => 'dashboard/currency_worth/edit',
                    'icon'  => 'fa fa-plus'

                ],

            ]
        ],
        [
            'text'         => 'Settings',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'         => 'fa fa-fw fa-cog',
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'General',
                    'url'  => 'dashboard/settings',
                    'icon'  => 'fa fa-gears'
                ],
                [
                    'text' => 'Prohibited Words',
                    'url'  => 'dashboard/settings/prohibited-words',
                    'icon'  => 'fa fa-comment-slash'

                ],
                [
                    'text' => 'App Version',
                    'url'  => 'dashboard/mobileversion/edit',
                    'icon'  => 'fa fa-mobile'

                ],

            ]
        ],

        [
            'text'        => 'Countries',
            'url'         => 'dashboard/country',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-home',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Country',
                    'url'  => 'dashboard/country/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show Country',
                    'url'  => 'dashboard/country',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Cities',
            'url'         => 'dashboard/city',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-home',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add City',
                    'url'  => 'dashboard/city/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show City',
                    'url'  => 'dashboard/city',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Categories',
            'url'         => 'dashboard/category',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tag',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Category',
                    'url'  => 'dashboard/category/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show Category',
                    'url'  => 'dashboard/category',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Tags',
            'url'         => 'dashboard/tag',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Tag',
                    'url'  => 'dashboard/tag/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show Tag',
                    'url'  => 'dashboard/tag',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],

        [
            'text'        => 'Terms',
            'url'         => 'dashboard/terms',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [

                [
                    'text' => 'Show/Edit Terms',
                    'url'  => 'dashboard/terms',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],

        [
            'text'        => 'Condition',
            'url'         => 'dashboard/condition',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [

                [
                    'text' => 'Show/Edit Condition',
                    'url'  => 'dashboard/conditions',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Channels',
            'url'         => 'dashboard/channels',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [

                [
                    'text' => 'Show Channels',
                    'url'  => 'dashboard/channels',
                    'icon'  => 'fa fa-eye'

                ],
            ]
        ],
        [
            'text'        => 'Currency',
            'url'         => 'dashboard/currencies',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Currency',
                    'url'  => 'dashboard/currencies/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Currency',
                    'url'  => 'dashboard/currencies',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Gifts',
            'url'         => 'dashboard/gifts',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Gift',
                    'url'  => 'dashboard/gifts/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Gifts',
                    'url'  => 'dashboard/gifts',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'GuessGame Shapes ',
            'url'         => 'dashboard/guessgame/shapes',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Shapes',
                    'url'  => 'dashboard/guessgame/shapes/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show Shapes',
                    'url'  => 'dashboard/guessgame/shapes',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'GuessGame Packages ',
            'url'         => 'dashboard/guessgame/packages',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Package',
                    'url'  => 'dashboard/guessgame/packages/create',
                    'icon'  => 'fa fa-plus'

                ],
                [
                    'text' => 'Show Packages',
                    'url'  => 'dashboard/guessgame/packages',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Emojis',
            'url'         => 'dashboard/emojis',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Emoji',
                    'url'  => 'dashboard/emojis/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Emojis',
                    'url'  => 'dashboard/emojis',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Packages',
            'url'         => 'dashboard/packages',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Package',
                    'url'  => 'dashboard/packages/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Package',
                    'url'  => 'dashboard/packages',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Badges',
            'url'         => 'dashboard/badges',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Badge',
                    'url'  => 'dashboard/badges/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Badge',
                    'url'  => 'dashboard/badges',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Frames',
            'url'         => 'dashboard/frames',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Frame',
                    'url'  => 'dashboard/frames/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Frame',
                    'url'  => 'dashboard/frames',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Login Signs',
            'url'         => 'dashboard/loginsigns',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Signs',
                    'url'  => 'dashboard/loginsigns/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Signs',
                    'url'  => 'dashboard/loginsigns',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Backgrounds',
            'url'         => 'dashboard/backgrounds',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Background',
                    'url'  => 'dashboard/backgrounds/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Background',
                    'url'  => 'dashboard/backgrounds',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Events',
            'url'         => 'dashboard/events',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Event',
                    'url'  => 'dashboard/events/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Event',
                    'url'  => 'dashboard/events',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Ads',
            'url'         => 'dashboard/ads',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Ads',
                    'url'  => 'dashboard/ads/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Ads',
                    'url'  => 'dashboard/ads',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Local Channels',
            'url'         => 'dashboard/local/channel',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Add Channel',
                    'url'  => 'dashboard/local/channel/create',
                    'icon'  => 'fa fa-plus'

                ],

                [
                    'text' => 'Show Channel',
                    'url'  => 'dashboard/local/channel',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Reports',
            'url'         => 'dashboard/reports',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            // 'label'       => 4,
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Show Reports',
                    'url'  => 'dashboard/reports',
                    'icon'  => 'fa fa-eye'

                ]
            ]
        ],
        [
            'text'        => 'Games Slider',
            'url'         => 'dashboard/games-slider',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Games',
                    'url'  => 'dashboard/games-slider',
                    'icon'  => 'fa fa-eye'
        
                ],
                [
                    'text' => 'Add New Game',
                    'url'  => 'dashboard/games-slider/create',
                    'icon'  => 'fa fa-plus'
                ],
            ],
        ],
        [
            'text'        => 'Lucky Gifts',
            'url'         => 'dashboard/lucky-gift',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Lucky Gift',
                    'url'  => 'dashboard/lucky-gift',
                    'icon'  => 'fa fa-eye'
                ],
                [
                    'text' => 'Add New Gift',
                    'url'  => 'dashboard/lucky-gift/create',
                    'icon'  => 'fa fa-plus'
                ],
                [
                    'text' => 'Lucky Gift Statistics',
                    'url'  => 'dashboard/lucky-gift/show',
                    'icon'  => 'fa fa-eye'
                ],
                [
                    'text' => 'Lucky Rounds',
                    'url'  => 'dashboard/lucky-round',
                    'icon'  => 'fa fa-eye'
                ],
                [
                    'text' => 'Add New Rounds',
                    'url'  => 'dashboard/lucky-round/create',
                    'icon'  => 'fa fa-plus'
                ]
            ],
        ],
        [
            'text'        => 'Hekayah Gifts',
            'url'         => 'dashboard/moon-gift',
            'roles'        => ['admin', 'super_admin'],
            'can'          => 'full-access',
            'icon'        => 'fa fa-fw fa-tags',
            'label_color' => 'success',
            'submenu'     =>  [
                [
                    'text' => 'Hekayah Gift',
                    'url'  => 'dashboard/moon-gift',
                    'icon'  => 'fa fa-eye'
                ],
                [
                    'text' => 'Add New Gift',
                    'url'  => 'dashboard/moon-gift/create',
                    'icon'  => 'fa fa-plus'
                ],
                [
                    'text' => 'Hekayah Gift Statistics',
                    'url'  => 'dashboard/moon-gift/show',
                    'icon'  => 'fa fa-eye'
                ]
            ],
        ],
        //        [
        //            'text' => 'profile',
        //            'url'  => 'admin/settings',
        //            'icon' => 'fas fa-fw fa-user',
        //        ],
        //        [
        //            'text' => 'change_password',
        //            'url'  => 'admin/settings',
        //            'icon' => 'fas fa-fw fa-lock',
        //        ],
        //        [
        //            'text'    => 'multilevel',
        //            'icon'    => 'fas fa-fw fa-share',
        //            'submenu' => [
        //                [
        //                    'text' => 'level_one',
        //                    'url'  => '#',
        //                ],
        //                [
        //                    'text'    => 'level_one',
        //                    'url'     => '#',
        //                    'submenu' => [
        //                        [
        //                            'text' => 'level_two',
        //                            'url'  => '#',
        //                        ],
        //                        [
        //                            'text'    => 'level_two',
        //                            'url'     => '#',
        //                            'submenu' => [
        //                                [
        //                                    'text' => 'level_three',
        //                                    'url'  => '#',
        //                                ],
        //                                [
        //                                    'text' => 'level_three',
        //                                    'url'  => '#',
        //                                ],
        //                            ],
        //                        ],
        //                    ],
        //                ],
        //                [
        //                    'text' => 'level_one',
        //                    'url'  => '#',
        //                ],
        //            ],
        //        ],
        //        ['header' => 'labels'],
        //        [
        //            'text'       => 'important',
        //            'icon_color' => 'red',
        //        ],
        //        [
        //            'text'       => 'warning',
        //            'icon_color' => 'yellow',
        //        ],
        //        [
        //            'text'       => 'information',
        //            'icon_color' => 'aqua',
        //        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#612-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#613-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],
];
