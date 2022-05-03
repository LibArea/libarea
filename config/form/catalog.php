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
      'title'   => __('web.title'),
      'tl'      => 1,
      'arr'     => ['min' => 14, 'max' => 250, 'required' => true],
      'name'    => 'title'
    ], [
      'title'   => 'URL',
      'tl'      => 1,
      'arr'     => ['required' => true],
      'name'    => 'url',
    ],  [
      'title'   => __('web.status'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'status',
    ], [
      'title'   => __('web.description'),
      'tl'      => 1,
      'arr'     => ['type' => 'textarea', 'required' => true],
      'name'    => 'content',
    ],  [
      'title'   => __('web.deny_replies'),
      'tl'      => 1,
      'arr'     => ['options' => ['0' => __('web.no'), '1' => __('web.yes')], 'type' => 'select'],
      'name'    => 'close_replies',
    ], [
      'title'   => __('web.posted'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => __('web.no'), '1' => __('web.yes')], 'type' => 'select'],
      'name'    => 'published',
    ], [
      'title'   => __('web.there_program'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => __('web.no'), '1' => __('web.yes')], 'type' => 'select', 'before_html' => '<h2>Soft</h2>'],
      'name'    => 'soft',
    ], [
      'title'   => __('web.hosted_github'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['options' => ['0' => __('web.no'), '1' => __('web.yes')], 'type' => 'select'],
      'name'    => 'github',
    ], [
      'title'   => __('web.url_github'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'github_url',
    ], [
      'title'   => __('web.title'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['required' => false],
      'name'    => 'title_soft',
    ], [
      'title'   => __('web.description'),
      'tl'      => UserData::REGISTERED_ADMIN,
      'arr'     => ['type' => 'textarea', 'required' => false],
      'name'    => 'content_soft',
    ]
  ]
];
