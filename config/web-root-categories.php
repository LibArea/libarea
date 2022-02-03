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
                    'title' => Translate::get('software'),
                    'url'   => 'software',
                    'sub'   => '',
                ],  
                [
                    'title' => Translate::get('internet'),
                    'url'   => 'internet',
                    'sub'   => '',
                ],  
                [
                    'title' => Translate::get('CMS'),
                    'url'   => 'cms',
                    'sub'   => '',
                ],  
        
        ],
    ], [
        'title' => Translate::get('reference.info'),
        'url'   => 'reference',
        'help'  => Translate::get('reference.help'),
        'sub'   => [], 
         
    ], [
        'title' => Translate::get('news.media'),
        'url'   => 'news',
        'sub'   => [],
    ], [
        'title' => Translate::get('science.education'),
        'url'   => 'science',
        'sub'   => [],
    ], [
        'title' => Translate::get('goods.services'),
        'url'   => 'business',
        'sub'   => [],
    ],
]; 