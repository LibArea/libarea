<?php
/*
 * Topics, Blogs and Pages
 * Темы, Блоги и Страницы
 */

return [
  // The number of topics and blogs that the participant reads on the sidebar
  // Количество тем и блогов которые читает участник на боковой панели
  'quantity_home' => 5,

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
