<?php
/*
 * Настройка титульной страницы каталога сайтов
 * Setting up the cover page of the site directory 
 */

return [
    [
        'title' => 'Веб-разработка',
        'url'   => 'web-development',
        'sub'   => [
                [
                    'title' => 'SEO',
                    'url'   => 'seo',
                    'sub'   => '',
                ],             
            ],
    ], [
        'title' => Translate::get('sites'),
        'url'   => 'sites',
        'sub'   => '',
    ]
]; 