<?php
/*
 * Topics, Blogs and Pages
 * Темы, Блоги и Страницы
 */

return [
  // The number of topics and blogs that the participant reads on the sidebar
  // Количество тем и блогов которые читает участник на боковой панели
  'quantity_home' => 5,

  // Pages with information about the site (links in the footer of the site)
  // Note that the data is not only the SLUG of the page, but also has a translation
  // Страницы с информацией про сайт (ссылки в подвале сайта)
  // Обратите внимание, что данные является не только SLUG страницы, но имеют ещё и перевод
  'page' => ['information', 'privacy', 'donate'],

  // To check existing facet types
  // Для проверки существующих типов фасетов
  'permitted'     => ['topic', 'blog', 'category', 'section'],

  // Types of faces
  // Типы граней
  'facet_type' => [
    [
      'title' => 'app.topic',
      'value' => 'topic',
    ],
    [
      'title' => 'app.blog',
      'value' => 'blog',
    ],
    [
      'title' => 'app.section',
      'value' => 'section',
    ]
  ],

];
