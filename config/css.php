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
        'style'     => HLEB_GLOBAL_DIRECTORY . '/resources/views/default/scss/', 
        
        // Test template to test custom layout creation
        // Тестовый шаблон для проверки создания произвольного макета
        'test'      => HLEB_GLOBAL_DIRECTORY . '/resources/views/test/scss/',
    ],
];
