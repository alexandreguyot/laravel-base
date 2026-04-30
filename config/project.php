<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Formats de dates
    |--------------------------------------------------------------------------
    */
    'date_format'               => 'd/m/Y',
    'time_format'               => 'H:i:s',
    'datetime_format'           => 'd/m/Y H:i:s',
    'flatpickr_date_format'     => 'd/m/Y',
    'flatpickr_time_format'     => 'H:i:S',
    'flatpickr_datetime_format' => 'd/m/Y H:i:S',

    /*
    |--------------------------------------------------------------------------
    | Langues supportées
    |--------------------------------------------------------------------------
    */
    'supported_languages' => [
        ['title' => 'Français', 'short_code' => 'fr'],
        ['title' => 'Anglais',  'short_code' => 'en'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rôle par défaut des nouveaux utilisateurs
    |--------------------------------------------------------------------------
    */
    'registered_user_role' => 'User',

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'options' => [10, 25, 50, 100],
    ],

    /*
    |--------------------------------------------------------------------------
    | Interface — Navigation
    | Valeurs possibles : 'left' (sidebar gauche) | 'top' (barre du haut)
    |--------------------------------------------------------------------------
    */
    'nav_layout' => env('NAV_LAYOUT', 'left'),

    /*
    |--------------------------------------------------------------------------
    | Interface — Thème DaisyUI par défaut
    | Valeurs : light | dark | corporate | business | cupcake | nord
    |--------------------------------------------------------------------------
    */
    'ui_theme' => env('UI_THEME', 'light'),

    /*
    |--------------------------------------------------------------------------
    | Interface — Bibliothèque d'icônes
    | Valeurs : 'lucide' | 'fontawesome' | 'heroicons'
    |--------------------------------------------------------------------------
    */
    'icon_library' => env('ICON_LIBRARY', 'lucide'),

    /*
    |--------------------------------------------------------------------------
    | Thèmes disponibles (présentés dans l'UI)
    |--------------------------------------------------------------------------
    */
    'available_themes' => [
        'light'     => 'Light',
        'dark'      => 'Dark',
        'corporate' => 'Corporate',
        'business'  => 'Business',
        'cupcake'   => 'Cupcake',
        'nord'      => 'Nord',
    ],

];
