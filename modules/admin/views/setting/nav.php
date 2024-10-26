<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'admin.settings.general',
        'url'   => url('admin.settings.general'),
        'name'  => __('admin.settings'),
      ], [
        'id'    => 'audits.all',
        'url'   => url('admin.settings.interface'),
        'name'  => __('admin.interface'),
      ], [
        'id'    => 'audits.all',
        'url'   => url('admin.settings.advertising'),
        'name'  => __('admin.advertising'),
      ]
    ]
  ]
);
?>