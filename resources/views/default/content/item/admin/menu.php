<ul class="nav scroll-menu">
  <?= insert('/_block/navigation/nav', [
    'list' => [
      [
        'url'   => url('web.comments'),
        'title' => __('web.comments'),
        'id'    => 'comments',
      ], [
        'url'   => url('web.deleted'),
        'title' => __('web.deleted'),
        'id'    => 'deleted',
      ], [
        'url'   => url('web.audits'),
        'title' => __('web.audits'),
        'id'    => 'audits',
      ], [
        'url'   => url('web.status'),
        'title' => __('web.status'),
        'id'    => 'status',
      ],
    ]
  ]); ?>
</ul>