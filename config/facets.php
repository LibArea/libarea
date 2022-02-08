<?php
/*
 * Topics, Blogs and Pages
 * Темы, Блоги и Страницы
 */

return [
    // The number of topics and blogs that the participant reads on the sidebar
    // Количество тем и блогов которые читает участник на боковой панели
    'quantity_home' => 10,

    // Pages with information about the site (link in the footer of the site) 
    // The URL must match the SLUG of the posts attached to the Info facet
    // Страницы с информацией про сайт (ссылка в подвале сайта)
    // URL должен совпадать с SLUG постов прикрепленного с фасету Информация
    'page-one'      => 'information',
    'page-two'      => 'privacy',

    // Default facets settings for an unauthorized participant
    // Настройки тем по умолчанию для не авторизованного участника
    'default' => [
        [
            'img'   => '/uploads/facets/logos/t-7-1625151409.jpeg',
            'name'  => Translate::get('frameworks'),
            'url'   => '/topic/framework',
        ], [
            'img'   => '/uploads/facets/logos/t-1-1625149922.jpeg',
            'name'  => Translate::get('SEO'),
            'url'   => '/topic/seo',
        ], [
            'img'   => '/uploads/facets/logos/t-2-1625149821.jpeg',
            'name'  => Translate::get('interesting sites'),
            'url'   => '/topic/sites',
        ], [
            'img'   => '/uploads/facets/logos/t-14-1625426679.jpeg',
            'name'  => Translate::get('facts'),
            'url'   => '/topic/facts',
        ], [
            'img'   => '/uploads/facets/logos/t-23-1626332348.jpeg',
            'name'  => Translate::get('psychology'),
            'url'   => '/topic/psychology',
        ]
    ],
    
    // Types of faces
    // Типы граней
    'facet_type' => [
      [
        'title' => Translate::get('topic'),
        'value' => 'topic',
      ], [
        'title' => Translate::get('blog'),
        'value' => 'blog',
      ], [
        'title' => Translate::get('section'),
        'value' => 'section',
      ], [
        'title' => Translate::get('category'),
        'value' => 'category',
      ]
    ],
    
];
