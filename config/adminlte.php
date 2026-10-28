<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'CEI - UAGRM',
    'title_prefix' => '',
    'title_postfix' => ' | Centro de Extensión de Idiomas',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>CEI</b> UAGRM',
    'logo_img' => 'img/logo_cei.png',
    'logo_img_class' => 'brand-image img-circle elevation-3 bg-white',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'CEI UAGRM Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => 'img/logo_cei.png',
            'alt' => 'CEI UAGRM',
            'class' => 'img-circle elevation-3 bg-white p-1 mb-2',
            'width' => 110,
            'height' => 110,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'img/logo_cei.png',
            'alt' => 'CEI UAGRM',
            'effect' => 'animation__shake',
            'width' => 100,
            'height' => 100,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
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
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
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
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
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
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
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
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'text' => 'Inicio / Dashboard',
            'url'  => 'home',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],

        ['header' => 'MÓDULOS DE GESTIÓN ACADÉMICA'],

        // =========================================================================
        // SEGURIDAD Y CONFIGURACIÓN
        // =========================================================================
        [
            'text'    => 'Seguridad y Configuración',
            'icon'    => 'fas fa-fw fa-shield-alt',
            'submenu' => [
                [
                    'text' => 'Configuración Institucional',
                    'url'  => 'admin/configuracion',
                    'icon' => 'fas fa-fw fa-university',
                    'can'  => 'admin.configuracion.index',
                ],
                [
                    'text' => 'Gestión de Usuarios',
                    'url'  => 'admin/usuarios',
                    'icon' => 'fas fa-fw fa-users-cog',
                    'can'  => 'admin.usuarios.index',
                ],
                [
                    'text' => 'Roles y Permisos',
                    'url'  => 'admin/roles',
                    'icon' => 'fas fa-fw fa-user-tag',
                    'can'  => 'admin.roles.index',
                ],
            ],
        ],

        // =========================================================================
        // CATÁLOGOS ACADÉMICOS
        // =========================================================================
        [
            'text'    => 'Catálogos Académicos',
            'icon'    => 'fas fa-fw fa-book-open',
            'submenu' => [
                [
                    'text' => 'Gestión de Idiomas',
                    'url'  => 'admin/idiomas',
                    'icon' => 'fas fa-fw fa-language',
                ],
                [
                    'text' => 'Módulos y Niveles',
                    'url'  => 'admin/modulos',
                    'icon' => 'fas fa-fw fa-layer-group',
                ],
                [
                    'text' => 'Ciclos y Paralelos',
                    'url'  => 'admin/paralelos',
                    'icon' => 'fas fa-fw fa-chalkboard',
                ],
            ],
        ],
        // =========================================================================
        // PERSONAL Y DOCENCIA
        // =========================================================================
        [
            'text'    => 'Personal y Docencia',
            'icon'    => 'fas fa-fw fa-chalkboard-teacher',
            'submenu' => [
                [
                    'text' => 'Padrón de Personal',
                    'url'  => 'admin/personal',
                    'icon' => 'fas fa-fw fa-id-badge',
                ],
                [
                    'text' => 'Formación Académica',
                    'url'  => 'admin/personal/formaciones',
                    'icon' => 'fas fa-fw fa-graduation-cap',
                ],
                [
                    'text' => 'Asignación a Grupos',
                    'url'  => 'admin/asignaciones',
                    'icon' => 'fas fa-fw fa-tasks',
                ],
            ],
        ],

        // =========================================================================
        // ESTUDIANTES Y BOLETAS
        // =========================================================================
        [
            'text'    => 'Estudiantes y Boletas',
            'icon'    => 'fas fa-fw fa-user-graduate',
            'submenu' => [
                [
                    'text' => 'Padrón de Estudiantes',
                    'url'  => 'admin/estudiantes',
                    'icon' => 'fas fa-fw fa-users',
                ],
                [
                    'text' => 'Registro de Boletas',
                    'url'  => 'admin/boletas',
                    'icon' => 'fas fa-fw fa-file-invoice-dollar',
                ],
                [
                    'text' => 'Kárdex Académico',
                    'url'  => 'admin/estudiantes/kardex',
                    'icon' => 'fas fa-fw fa-address-book',
                ],
            ],
        ],

        // =========================================================================
        // INSCRIPCIONES Y MATRÍCULA
        // =========================================================================
        [
            'text'    => 'Inscripciones y Matrícula',
            'icon'    => 'fas fa-fw fa-clipboard-check',
            'submenu' => [
                [
                    'text' => 'Nueva Inscripción',
                    'url'  => 'admin/inscripciones/create',
                    'icon' => 'fas fa-fw fa-user-plus',
                ],
                [
                    'text' => 'Listado de Matrículas',
                    'url'  => 'admin/inscripciones',
                    'icon' => 'fas fa-fw fa-list-alt',
                ],
                [
                    'text' => 'Historial y Anulaciones',
                    'url'  => 'admin/inscripciones/anulaciones',
                    'icon' => 'fas fa-fw fa-undo-alt',
                ],
            ],
        ],

        // =========================================================================
        // CONTROL DE CALIFICACIONES
        // =========================================================================
        [
            'text'    => 'Control de Calificaciones',
            'icon'    => 'fas fa-fw fa-marker',
            'submenu' => [
                [
                    'text' => 'Mis Grupos Asignados',
                    'url'  => 'docente/grupos',
                    'icon' => 'fas fa-fw fa-users',
                ],
                [
                    'text' => 'Cargar Nota Final',
                    'url'  => 'docente/calificaciones',
                    'icon' => 'fas fa-fw fa-edit',
                ],
                [
                    'text' => 'Actas Oficiales (PDF)',
                    'url'  => 'docente/actas',
                    'icon' => 'fas fa-fw fa-file-pdf',
                ],
            ],
        ],

        // =========================================================================
        // PORTAL DEL ESTUDIANTE
        // =========================================================================
        [
            'text'    => 'Portal del Estudiante',
            'icon'    => 'fas fa-fw fa-laptop-code',
            'submenu' => [
                [
                    'text' => 'Mi Módulo y Clases',
                    'url'  => 'estudiante/modulo-activo',
                    'icon' => 'fas fa-fw fa-video',
                ],
                [
                    'text' => 'Avance a Graduación',
                    'url'  => 'estudiante/avance-curricular',
                    'icon' => 'fas fa-fw fa-award',
                ],
                [
                    'text' => 'Historial y Certificados',
                    'url'  => 'estudiante/mis-documentos',
                    'icon' => 'fas fa-fw fa-certificate',
                ],
            ],
        ],

        // =========================================================================
        // REPORTES Y FINANZAS
        // =========================================================================
        [
            'text'    => 'Reportes y Finanzas',
            'icon'    => 'fas fa-fw fa-chart-line',
            'submenu' => [
                [
                    'text' => 'Conciliación de Boletas',
                    'url'  => 'admin/reportes/conciliacion-boletas',
                    'icon' => 'fas fa-fw fa-money-check-alt',
                ],
                [
                    'text' => 'Inscritos por Modalidad',
                    'url'  => 'admin/reportes/inscritos-modalidad',
                    'icon' => 'fas fa-fw fa-chart-pie',
                ],
                [
                    'text' => 'Graduados Intermedio/Avanzado',
                    'url'  => 'admin/reportes/estudiantes-graduados',
                    'icon' => 'fas fa-fw fa-user-check',
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
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
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */
    //se añadieron plugins para transformar a pdf y tambien a excel
    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.4.0/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.4.0/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.4.0/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.4.0/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.4.0/js/buttons.colVis.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.4.0/css/buttons.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
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
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
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

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
