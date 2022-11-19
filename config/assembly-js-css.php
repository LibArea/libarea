<?php
/*
 * Style settings (css)
 * Настройки для стилей (css)
 */

return [

    // Path to svg sprite
    // Путь к спрайту svg
    'svg_path'     => HLEB_PUBLIC_DIR . '/assets/svg/icons.svg',
    
    // Paths to template files
    'path_css' => [
        // Base path to css files
        // Базовый путь к css файлам
        'style'     => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/css/', 
        
        // Template for minimum style
        // Шаблон для стиля minimum
        'minimum'   => HLEB_GLOBAL_DIRECTORY . '/resources/views/minimum/css/',
    ],
    
    'path_js' => [
        // Base path to js files
        // Базовый путь к js файлам
        'common'        => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/', 
        'admin'         => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/', 
        'medium-zoom'   => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/',
        'app'           => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/', 
        'uploads'       => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/js/',
     ],  
];
