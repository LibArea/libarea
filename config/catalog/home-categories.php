<?php
/*
 * Настройка титульной страницы каталога сайтов
 * Setting up the cover page of the site directory 
 */ 

// Actually this file has a temporary solution 
// https://stackoverflow.com/questions/3077305/how-to-use-multilanguage-database-schema-with-orm/4745863#4745863
return [
    [
        'title' => 'Hi-Tech',
        'url'   => 'hi-tech',
        'sub'   => [
                [
                    'title' => __('web.software'),
                    'url'   => 'software',
                    'sub'   => '',
                ],  
                [
                    'title' => __('web.internet'),
                    'url'   => 'internet',
                    'sub'   => '',
                ],  
                [
                    'title' => __('web.cms'),
                    'url'   => 'content-management-system',
                    'sub'   => '',
                ],  
        
        ],
    ], [
        'title' => __('web.reference_info'),
        'url'   => 'reference',
        'help'  => __('web.reference_help'),
        'sub'   => [], 
         
    ], [
        'title' => __('web.news'),
        'url'   => 'media',
        'sub'   => [],
    ], [
        'title' => __('web.life'),
        'url'   => 'private-life',
        'help'  => __('web.life_help'),
        'sub'   => [],
    ], [
        'title' => __('web.science'),
        'url'   => 'science',
        'sub'   => [],
    ], [
        'title' => __('web.goods'),
        'url'   => 'business',
        'sub'   => [],
    ], [
        'title' => __('web.culture'),
        'url'   => 'culture-arts',
        'sub'   => [],
    ],
]; 