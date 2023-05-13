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
    'svg_path'     => HLEB_PUBLIC_DIR . '/assets/svg/icons.svg',
    
    // Paths to template files
    'path_css' => [
        // Base path to css files
        // Базовый путь к css файлам
        'style'     => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/css/', 
        
        // Template for qa style
        // Шаблон для стиля qa
        'qa'   => HLEB_GLOBAL_DIRECTORY . '/resources/views/qa/css/',
        
        // Template for minimum style
        // Шаблон для стиля minimum
        'minimum'   => HLEB_GLOBAL_DIRECTORY . '/resources/views/minimum/css/',
    ],
    
    'path_js' => [
        // Base path to js files
        // Базовый путь к js файлам
        'la'            => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/',
        'common'        => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/', 
        'admin'         => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/', 
        'medium-zoom'   => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/',
        'app'           => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/', 
        'uploads'       => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/',
        'catalog'       => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/',
     ],  
];
