<div class="nav-bar">
  <ul class="nav scroll-menu">
    <?= insert('/_block/navigation/nav', [
      'list' => [
        [
          'id'    => 'favorites',
          'url'   => url('favorites'),
          'title' => 'app.favorites',
        ], [
          'id'    => 'read',
          'url'   => url('read'),
          'title' => 'app.i_read',
        ], [
          'id'    => 'subscribed',
          'url'   => url('subscribed'),
          'title' => 'app.subscribed',
        ], [
          'id'    => 'folders',
          'url'   => url('favorites.folders'),
          'title' => 'app.folders',
        ],
      ],
    ]); ?>
  </ul>
</div>