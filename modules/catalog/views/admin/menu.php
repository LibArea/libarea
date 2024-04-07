<ul class="nav scroll-menu">
  <?= insert('/_block/navigation/nav', [
    'list' => [
      [
        'url'   => url('web.comments'),
        'title' => 'web.comments',
        'id'    => 'comments',
      ], [
        'url'   => url('web.deleted'),
        'title' => 'web.deleted',
        'id'    => 'deleted',
      ], [
        'url'   => url('web.audits'),
        'title' => 'web.audits',
        'id'    => 'audits',
      ], [
        'url'   => url('web.status', ['code' => 301]),
        'title' => 'web.status',
        'id'    => 'status',
      ],
    ]
  ]); ?>
</ul>