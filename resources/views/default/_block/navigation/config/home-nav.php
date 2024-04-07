<?= insert(
  '/_block/navigation/nav',
  [
    'list' =>  [
      [
        // 'tl'    => 1,
        'id'    => 'main.all',
        'url'   => url('main.all'),
        'title' => 'app.all',
      ], [
        'id'    => 'main.feed',
        'url'   => url('home'),
        'title' => 'app.feed',
      ], [
        'id'    => 'main.posts',
        'url'   => url('main.posts'),
        'title' => 'app.posts',
      ], [
        'id'    => 'main.questions',
        'url'   => url('main.questions'),
        'title' => 'app.questions',
      ],
      /* [
      'id'    => 'main.top',
      'url'   => 'main.top',
      'title' => 'app.top',
    ], */
      [
        'tl'    => 10,
        'id'    => 'main.deleted',
        'url'   => url('main.deleted'),
        'title' => 'app.deleted',
      ],
    ],
  ]
);
?>