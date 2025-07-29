<?php
/*
 * Meta tag settings
 * Настройки мета-тегов
 */

return [
    'url'               => 'https://libarea.ru',

    // SEO
    'name'              => 'LibArea',
    'title'             => 'LibArea — сообщество (скрипт мультиблога)',
    'img_path'          => '/assets/images/libarea.jpg',

    /*
    * The following language strings are used for the meta description 
    * of the central (main) page, site feed.
    *
    * Следующие языковые строки используются для мета- описания 
    * центральной (главной) странице, ленты сайта.
    */
    'feed_title'        => 'LibArea — сообщество (скрипт мультиблога)',
    'top_title'         => 'LibArea — популярные посты',
    'all_title'         => 'LibArea — все посты',
    'deleted_title'     => 'LibArea — удаленные посты',
    'deleted_desc'      => 'LibArea. Все удаленные посты на сайте',
    'feed_desc'         => 'Темы по интересам, лента, блоги. Каталог сайтов. Платформа для коллективных блогов, скрипт мультиблога, сообщества LibArea.',
    'top_desc'          => 'Список популярных постов в ленте сообщества (по количеству ответов). Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт LibArea.',
    'all_desc'          => 'Список всех постов в ленте сообщества. Скрипт LibArea.',

    'question_title'    => 'LibArea — вопросы и ответы',
    'question_desc'     => 'Список всех вопросов и ответов в сообществе в хронологическом порядке. Сервис Q&A LibArea.',
    'post_title'        => 'LibArea — посты в ленте',
    'post_desc'         => 'Посты в ленте сообщества. Тематические публикации, подборка интересных статей. ',
    'note_title'        => 'LibArea — Заметки  в ленте',
    'note_desc'         => 'Заметки добавленные через URL с других сайтов. Сортировка по популярности и доменам.',
    'article_title'     => 'LibArea — cтатьи в ленте',
    'article_desc'      => 'Cтатьи в ленте сообщества. Тематические публикации, подборка интересных статей.',

    // For the main page - the banner title and text
    // Для главной - заголовок и текст баннера
    'banner_title'      => 'LibArea — сообщество',
    'banner_desc'       => 'Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт мультиблога',

    // For site directory
    // Для каталог сайтов
    'img_path_web'      => '/assets/images/libarea-web.png',

    // If false, then the URL of the posts will be: /post/id
    // otherwise: /post/id/slug   
    // Если false, то URL постов будет: /post/id
    // в противном случае: /post/id/slug
    'slug_post'         => true,

    // Create (true) or not an IMG of a post (for og:image markup) from the title, the author's nickname and his avatar (+ background)
    // Создавать (true) или нет IMG поста (для разметки og:image) из заголовка, ника автора и его аватара (+ фон)
    'img_generate'      => false,
];
