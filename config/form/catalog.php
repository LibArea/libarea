<?php
/*
 * Settings for the catalog 
 * Настройки для каталога
 */
$ch = Translate::get('characters');
$fe = Translate::get('for example');
return [
  // Fields for adding and editing a site in the catalog 
  // Поля для добавления и редактирования сайта в каталоге
  'site' =>  [
    [
      'title'   => Translate::get('title'),
      'tl'      => 2,
      'arr'     => ['min' => 14, 'max' => 250, 'required' => true, 'help' => '> 14 ' . $ch . '. ' . $fe . ': «Agouti» — сообщество'],
      'name'    => 'title'
    ], [
      'title'   => 'URL',
      'tl'      => 2,
      'arr'     => ['required' => true, 'help' => $fe . ': https://google.ru'],
      'name'    => 'url',
    ],  [
      'title'   => Translate::get('status'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'status',
    ], [
      'title'   => Translate::get('description'),
      'tl'      => 2,
      'arr'     => ['type' => 'textarea', 'required' => true, 'help' => '> 25 ' . $ch],
      'name'    => 'content',
    ], [
      'title'   => Translate::get('posted'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'published',
    ], [
      'title'   => Translate::get('there.program'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select', 'before_html' => '<h3>Soft</h3>'],
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
