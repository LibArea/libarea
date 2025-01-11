<?php

return [

	/*
	 * Menu in the admin panel
	 * Меню в админ-панели
	 */
    'menu' => [
      /* Hide so as not to embarrass
        [
            'url'   => 'admin.settings.general',
            'title' => 'admin.settings',
            'icon'  => 'settings',
            'id'    => 'settings',
        ], */
        [
            'url'   => 'admin.logs',
            'title' => 'admin.logs',
            'icon'  => 'activity',
            'id'    => 'logs',
        ], [
            'url'   => 'admin.audits',
            'title' => 'admin.audits',
            'icon'  => 'flag',
            'id'    => 'audits',
        ], [
            'url'   => 'admin.words',
            'title' => 'admin.words',
            'icon'  => 'stop',
            'id'    => 'words',
        ], [
            'url'   => 'admin.invitations',
            'title' => 'admin.invites',
            'icon'  => 'mail',
            'id'    => 'invitations',
        ], [
            'url'   => 'admin.badges',
            'title' => 'admin.badges',
            'icon'  => 'award',
            'id'    => 'badges',
        ], [
            'url'   => 'admin.logs.search',
            'title' => 'admin.search',
            'icon'  => 'search',
            'id'    => 'search',
        ], [
            'url'   => 'admin.css',
            'title' => 'admin.css',
            'icon'  => 'css',
            'id'    => 'css',
        ],
    ],
	
	/*
	 * Css и JS
	 */

    // Path to svg sprite
    // Путь к спрайту svg
    'svg_path'  => '/assets/svg/icons.svg',
    
    // Paths to template files
	// Separate style files that may not be included in the templates
	// Отдельные файлы стилей, которые могут не включаться в шаблоны
    'path_css' => [
        'rtl'       => '/resources/views/default/css/rtl.css',
        'catalog'   => '/modules/catalog/views/css/catalog.css',
    ],

    // Base path to js files
    // Базовый путь к js файлам    
    'path_js' => [
        'la'            => '/resources/views/default/js/',
        'common'        => '/resources/views/default/js/', 
        'admin'         => '/resources/views/default/js/', 
        'zooom'   		=> '/resources/views/default/js/',
        'app'           => '/resources/views/default/js/', 
        'catalog'       => '/resources/views/default/js/',
     ],  

];