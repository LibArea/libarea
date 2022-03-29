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
      'tl'      => 2,
      'arr'     => ['min' => 14, 'max' => 250, 'required' => true],
      'name'    => 'title'
    ], [
      'title'   => 'URL',
      'tl'      => 2,
      'arr'     => ['required' => true],
      'name'    => 'url',
    ],  [
      'title'   => Translate::get('status'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'status',
    ], [
      'title'   => Translate::get('description'),
      'tl'      => 2,
      'arr'     => ['type' => 'textarea', 'required' => true],
      'name'    => 'content',
    ],  [
      'title'   => Translate::get('deny.replies'),
      'tl'      => 2,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'close_replies',
    ], [
      'title'   => Translate::get('posted'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'published',
    ], [
      'title'   => Translate::get('there.program'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select', 'before_html' => '<h2>Soft</h2>'],
      'name'    => 'soft',
    ], [
      'title'   => Translate::get('hosted.github'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'github',
    ], [
      'title'   => Translate::get('url.address.github'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'github_url',
    ], [
      'title'   => Translate::get('title'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'title_soft',
    ], [
      'title'   => Translate::get('description'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['type' => 'textarea', 'required' => false],
      'name'    => 'content_soft',
    ]
  ],
];
