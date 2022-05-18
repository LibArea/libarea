<?php
/*
 * Настройка титульной страницы каталога сайтов
 * Setting up the cover page of the site directory 
 */ 

return [
    [
        'title' => __('web.internet'),
        'url'   => 'internet',
        'sub'   => [
                [
                    'title' => __('web.security'),
                    'url'   => 'security',
                    'sub'   => '',
                ],
        
        ],
    ], [
        'title' => __('web.reference_info'),
        'url'   => 'reference',
        'help'  => __('web.reference_help'),
        'sub'   => [], 
         
    ],
];