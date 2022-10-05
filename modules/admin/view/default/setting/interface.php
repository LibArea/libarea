<?= includeTemplate(
  '/view/default/menu',
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
      ]
    ]
  ]
); ?>

<div class="box bg-white">
  interface
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>