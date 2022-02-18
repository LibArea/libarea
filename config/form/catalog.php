<?php
/*
 * Settings for the catalog 
 * Настройки для каталога
 */

return [
  // Fields for adding and editing a site in the catalog 
  // Поля для добавления и редактирования сайта в каталоге
  'site' =>  [
    [
      'title'   => Translate::get('title'),
      'tl'      => 1,
      'arr'     => ['min' => 14, 'max' => 250, 'required' => true],
      'name'    => 'title'
    ], [
      'title'   => 'URL',
      'tl'      => 1,
      'arr'     => ['required' => true],
      'name'    => 'url',
    ],  [
      'title'   => Translate::get('status'),
      'tl'      => 10,
      'arr'     => ['required' => false],
      'name'    => 'status',
    ], [
      'title'   => Translate::get('description'),
      'tl'      => 1,
      'arr'     => ['type' => 'textarea', 'required' => true],
      'name'    => 'content',
    ], [
      'title'   => Translate::get('posted'),
      'tl'      => 10,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'published',
    ], [
      'title'   => Translate::get('there.program'),
      'tl'      => 10,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select', 'before_html' => '<h3>Soft</h3>'],
      'name'    => 'soft',
    ], [
      'title'   => Translate::get('hosted.github'),
      'tl'      => 10,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'github',
    ], [
      'title'   => Translate::get('url.address.github'),
      'tl'      => 10,
      'arr'     => ['required' => false],
      'name'    => 'github_url',
    ], [
      'title'   => Translate::get('title'),
      'tl'      => 10,
      'arr'     => ['required' => false],
      'name'    => 'title_soft',
    ], [
      'title'   => Translate::get('description'),
      'tl'      => 10,
      'arr'     => ['type' => 'textarea', 'required' => false],
      'name'    => 'content_soft',
    ]
  ],
];
