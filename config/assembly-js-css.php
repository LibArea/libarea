<?php
/*
 * Style settings (css)
 * Настройки для стилей (css)
 */

return [

    // To force an update
    'version'     => 14,

    // Path to svg sprite
    // Путь к спрайту svg
    'svg_path'  => '/assets/svg/icons.svg',
    
    // Paths to template files
    'path_css' => [
        // Base path to css files
        // Базовый путь к css файлам
        'style' => '/resources/views/default/css/build.css', 
        
        // Template for qa style
        // Шаблон для стиля qa
        'qa'    => '/resources/views/qa/css/build.css',
        
        // Template for minimum style
        // Шаблон для стиля minimum
        'minimum'   => '/resources/views/minimum/css/build.css',
        
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
