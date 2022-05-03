<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'statistics',
        'url'   => url('admin.search'),
        'name'  => __('admin.statistics'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'query',
        'url'   => url('admin.search.query'),
        'name'  => __('admin.query'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'schemas',
        'url'   => url('admin.search.schemas'),
        'name'  => __('admin.schemas'),
        'icon'  => 'bi-x-circle',
      ]
    ]
  ]
); ?>

 