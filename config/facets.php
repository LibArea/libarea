<?php
/*
 * Topics, Blogs and Pages
 * Темы, Блоги и Страницы
 */

return [
    // The number of topics and blogs that the participant reads on the sidebar
    // Количество тем и блогов которые читает участник на боковой панели
    'quantity_home' => 5,

    // Pages with information about the site (link in the footer of the site) 
    // The URL must match the SLUG of the posts attached to the Info facet
    // Страницы с информацией про сайт (ссылка в подвале сайта)
    // URL должен совпадать с SLUG постов прикрепленного с фасету Информация
    'page-one'      => 'information',
    'page-two'      => 'privacy',
    
    // To check existing facet types
    // Для проверки существующих типов фасетов
    'permitted'     => ['topic', 'blog', 'category', 'section'],

    // Types of faces
    // Типы граней
    'facet_type' => [
      [
        'title' => __('app.topic'),
        'value' => 'topic',
      ],
      [
        'title' => __('app.blog'),
        'value' => 'blog',
      ],
      [
        'title' => __('app.section'),
        'value' => 'section',
      ]
    ],
    
];
