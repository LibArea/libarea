<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'statistics',
        'url'   => url('admin.search'),
        'name'  => __('statistics'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'query',
        'url'   => url('admin.search.query'),
        'name'  => __('query'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'schemas',
        'url'   => url('admin.search.schemas'),
        'name'  => __('schemas'),
        'icon'  => 'bi-x-circle',
      ]
    ]
  ]
); ?>

 