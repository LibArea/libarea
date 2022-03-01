<?php
/*
 * Настройка титульной страницы каталога сайтов
 * Setting up the cover page of the site directory 
 */ 

// Actually this file has a temporary solution 
// https://stackoverflow.com/questions/3077305/how-to-use-multilanguage-database-schema-with-orm/4745863#4745863
return [
    [
        'title' => Translate::get('internet'),
        'url'   => 'internet',
        'sub'   => [
                [
                    'title' => Translate::get('Security'),
                    'url'   => 'security',
                    'sub'   => '',
                ],
        
        ],
    ], [
        'title' => Translate::get('reference.info'),
        'url'   => 'reference',
        'help'  => Translate::get('reference.help'),
        'sub'   => [], 
         
    ],
];