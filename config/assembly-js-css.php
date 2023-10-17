<?php
/*
 * Style settings (css)
 * Настройки для стилей (css)
 */

return [

    // To force an update
    'version'   => 7,

    // Path to svg sprite
    // Путь к спрайту svg
    'svg_path'  => '/assets/svg/icons.svg',
    
    // Paths to template files
    'path_css' => [
        // Single templates without assembly
        // Одиночные шаблоны без сборки
        'rtl'       => '/resources/views/default/css/rtl.css',
        'catalog'   => '/resources/views/default/css/catalog.css',
    ],
    
    'path_js' => [
        // Base path to js files
        // Базовый путь к js файлам
        'la'            => '/resources/views/default/js/',
        'common'        => '/resources/views/default/js/', 
        'admin'         => '/resources/views/default/js/', 
        'medium-zoom'   => '/resources/views/default/js/',
        'app'           => '/resources/views/default/js/', 
        'uploads'       => '/resources/views/default/js/',
        'catalog'       => '/resources/views/default/js/',
     ],  
];
