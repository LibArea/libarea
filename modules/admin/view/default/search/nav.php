<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'statistics',
        'url'   => getUrlByName('admin.search'),
        'name'  => __('statistics'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'query',
        'url'   => getUrlByName('admin.search.query'),
        'name'  => __('query'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'schemas',
        'url'   => getUrlByName('admin.search.schemas'),
        'name'  => __('schemas'),
        'icon'  => 'bi-x-circle',
      ]
    ]
  ]
); ?>

 