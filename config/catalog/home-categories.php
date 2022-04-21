<?php
/*
 * Настройка титульной страницы каталога сайтов
 * Setting up the cover page of the site directory 
 */ 

// Actually this file has a temporary solution 
// https://stackoverflow.com/questions/3077305/how-to-use-multilanguage-database-schema-with-orm/4745863#4745863
return [
    [
        'title' => __('internet'),
        'url'   => 'internet',
        'sub'   => [
                [
                    'title' => __('security'),
                    'url'   => 'security',
                    'sub'   => '',
                ],
        
        ],
    ], [
        'title' => __('reference.info'),
        'url'   => 'reference',
        'help'  => __('reference.help'),
        'sub'   => [], 
         
    ],
];