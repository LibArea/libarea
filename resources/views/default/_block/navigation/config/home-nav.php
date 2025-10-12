<?= insert(
  '/_block/navigation/nav',
  [
    'list' =>  [
      [
        // 'tl'    => 1,
        'id'    => 'main.all',
        'url'   => url('main.all'),
        'title' => 'app.all',
      ],
      [
        'id'    => 'main.feed',
        'url'   => url('home'),
        'title' => 'app.feed',
      ],
      [
        'id'    => 'main.articles',
        'url'   => url('main.articles'),
        'title' => 'app.articles',
      ],
      [
        'id'    => 'main.posts',
        'url'   => url('main.posts'),
        'title' => 'app.posts',
      ],
      [
        'id'    => 'main.notes',
        'url'   => url('main.notes'),
        'title' => 'app.notes',
      ],
      [
        'id'    => 'main.questions',
        'url'   => url('main.questions'),
        'title' => 'app.questions',
      ],
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